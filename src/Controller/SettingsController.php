<?php

namespace Effiana\ConfigBundle\Controller;

use Doctrine\ORM\EntityManager;
use Effiana\ConfigBundle\Entity\Setting;
use Effiana\ConfigBundle\Form\Flow\SettingsFlow;
use Exception;
use ManagementSystemBundle\Event\MarketFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @author Christian Raue <christian.raue@gmail.com>
 * @copyright 2011-2019 Christian Raue
 * @license http://opensource.org/licenses/mit-license.php MIT License
 * @Route("/")
 */
class SettingsController extends AbstractController
{
    /**
     * @param Request $request
     * @return Response
     *
     * @Route("/", name="effiana_config_settings_index")
     */
    public function index(Request $request) {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $allStoredSettings = $em->getRepository('EffianaConfigBundle:Setting')->createQueryBuilder('effianaConfig')
            ->getQuery()->getArrayResult();

        $settingsBySection = [];
        /** @var Setting $setting */
        foreach ($allStoredSettings as $setting) {
            if($setting['type'] === 'file') {
                $setting['value'] = new File($setting['value'], false);
            } else {
                settype($setting['value'], $setting['type']);
            }

            $settingsBySection[$setting['section']][] = $setting;
        }

        return $this->render('@EffianaConfig/Settings/index.html.twig', [
            'settings' => $settingsBySection,
        ]);
    }

    /**
     * @param string $name
     * @param Request $request
     *
     * @return RedirectResponse|Response
     * @Route("/{name}/edit", name="effiana_config_settings_edit")
     * @Route("/add", name="effiana_config_settings_add")
     */
    public function edit(SettingsFlow $flow, ?string $name, Request $request)
    {
        $flashBag = $this->get('session')->getFlashBag();
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        if($name === null) {
            $setting = new Setting();
        } else {
            $setting = $em->getRepository('EffianaConfigBundle:Setting')->find($name);
        }

        if($setting instanceof Setting) {
            $flow->bind($setting);
            // form of the current step
            $form = $flow->createForm();
            if ($flow->isValid($form)) {

                $flow->saveCurrentStepData($form);
                if ($flow->nextStep()) {
                    // form for the next step
                    $form = $flow->createForm();
                } else {
                    $parameters = $request->files->all();
                    $uploadedFile = null;
                    if(isset($parameters['effiana_config_setting']['value']) && $parameters['effiana_config_setting']['value'] instanceof UploadedFile) {
                        /** @var UploadedFile $uploadedFile */
                        $uploadedFile = $parameters['effiana_config_setting']['value'];
                        unset($parameters['effiana_config_setting']['value']);
                    }
                    try {

                        if($uploadedFile instanceof UploadedFile) {
                            /** @var MarketFile $event */
                            $event = $this->get('event_dispatcher')->dispatch(
                                new MarketFile(
                                    $uploadedFile->getRealPath(),
                                    $uploadedFile->getClientOriginalName()
                                ), 'market.upload.file');
                            $fileUrl = sprintf('%s%s',$this->container->getParameter('upload.cdn_url'), $event->getUploaderUrl());
                            $settings = $form->getData();
                            $settings->setValue($fileUrl);
                        }

                        $setting->setSection('MAIN');

                        $em->persist($setting);
                        $em->flush();

                    } catch (Exception $ex) {
                        $flashBag->add('error', $ex->getMessage());
                    }

                    $flow->reset(); // remove step data from the session

                    return $this->redirectToRoute('effiana_config_settings_index');
                }
            }
//            /**
//             * Form for edit workflow step.
//             */
//            $form = $this->createForm(SettingType::class, $setting);
//            $form->handleRequest($request);
//            /*
//             * Saving workflow step from form
//             */
//            if ($form->isSubmitted() && $form->isValid()) {
//                $em->persist($setting);
//                $em->flush();
//
//                return $this->redirectToRoute('effiana_config_settings_index');
//            }

            return $this->render('@EffianaConfig/Settings/form.html.twig', [
                'form' => $form->createView(),
                'flow' => $flow,
                'setting' => $setting
            ]);
        }

        throw new NotFoundHttpException();
    }
}
