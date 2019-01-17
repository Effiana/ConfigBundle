<?php

namespace Effiana\ConfigBundle\Controller;

use Doctrine\ORM\EntityManager;
use Effiana\ConfigBundle\Entity\Setting;
use Effiana\ConfigBundle\Entity\SettingInterface;
use Effiana\ConfigBundle\Form\ModifySettingsForm;
use Effiana\ConfigBundle\Form\Type\SettingType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @author Christian Raue <christian.raue@gmail.com>
 * @copyright 2011-2019 Christian Raue
 * @license http://opensource.org/licenses/mit-license.php MIT License
 * @Route("/")
 */
class SettingsController extends Controller
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
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
            settype($setting['value'], $setting['type']);
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
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @Route("/{name}/edit", name="effiana_config_settings_edit")
     * @Route("/add", name="effiana_config_settings_add")
     */
    public function edit(?string $name, Request $request)
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        if($name === null) {
            $setting = new Setting();
        } else {
            $setting = $em->getRepository('EffianaConfigBundle:Setting')->find($name);
        }

        if($setting instanceof Setting) {
            /**
             * Form for edit workflow step.
             */
            $form = $this->createForm(SettingType::class, $setting);
            $form->handleRequest($request);
            /*
             * Saving workflow step from form
             */
            if ($form->isSubmitted() && $form->isValid()) {
                $em->persist($setting);
                $em->flush();

                return $this->redirectToRoute('effiana_config_settings_index');
            }

            return $this->render('@EffianaConfig/Settings/form.html.twig', [
                'form' => $form->createView(),
                'setting' => $setting
            ]);
        }

        throw new NotFoundHttpException();
    }

}
