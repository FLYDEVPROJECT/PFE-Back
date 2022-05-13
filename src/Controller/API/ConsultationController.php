<?php

namespace App\Controller\API;

use App\Entity\Consultation;
use App\Form\ConsultationType;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Annotation as ApiDoc;
use OpenApi\Annotations as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/consultation")
 * @OA\Tag(name="Consultation")
 * @ApiDoc\Security(name="Bearer")
 * @OA\Response(
 *     response=Response::HTTP_UNAUTHORIZED,
 *     description="JWT Token not found or invalid",
 *     @OA\JsonContent(
 *     type="object",
 *     default={"code": Response::HTTP_UNAUTHORIZED,"message":"JWT Token not found or invalid"},
 *     )
 * )
 * @OA\Response(
 *     response=Response::HTTP_NOT_FOUND,
 *     description="Returns nothing if parameters are wrong",
 *     @OA\JsonContent(
 *     type="string",
 *     default="",
 *     )
 * )
 */
class ConsultationController extends AbstractController
{
    /**
     * @Route("/", name="app_consultation_index", methods={"GET"})
     * @ApiDoc\Operation(
     *     summary="Get consultations."
     * )
     * @OA\Response(
     *     response=Response::HTTP_OK,
     *     description="Returns the brands for a specific category",
     *     @OA\JsonContent(
     *     type="array",
     *     @OA\Items(ref=@ApiDoc\Model(type=Consultation::class, groups={"consultation"}))
     *     )
     * )
     */
    public function index(EntityManagerInterface $entityManager): Response
    {
        $consultations = $entityManager
            ->getRepository(Consultation::class)
            ->findAll();

        return $this->json($consultations, Response::HTTP_OK, [], ['groups' => ['consultation']]);
    }

    /**
     * @Route("/new", name="app_consultation_new", methods={"POST"})
     * @ApiDoc\Operation(
     *     summary="Add consultation."
     * )
     * @OA\RequestBody(
     *     description="The field used to get brands by category",
     *     required=true,
     *     @OA\JsonContent(ref=@ApiDoc\Model(type=ConsultationType::class))
     * )
     * @OA\Response(
     *     response=Response::HTTP_OK,
     *     description="Returns the new consultation",
     *     @OA\JsonContent(
     *     type="object",
     *     ref=@ApiDoc\Model(type=Consultation::class, groups={"consultation"})
     *     )
     * )
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $consultation = new Consultation();
        $form = $this->createForm(ConsultationType::class, $consultation);
        $form->submit(json_decode($request->getContent(), true));
        if ($form->isValid()) {
            $entityManager->persist($consultation);
            $entityManager->flush();
            return $this->json($consultation, Response::HTTP_OK, [], ['groups' => ['consultation']]);
        }
        return $this->json($this->getErrorsFromForm($form), Response::HTTP_NOT_FOUND);
    }

    /**
     * @Route("/{id}", name="app_consultation_show", methods={"GET"})
     */
    public function show(Consultation $consultation): Response
    {
        return $this->render('consultation/show.html.twig', [
            'consultation' => $consultation,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_consultation_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Consultation $consultation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ConsultationType::class, $consultation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_consultation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('consultation/edit.html.twig', [
            'consultation' => $consultation,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_consultation_delete", methods={"POST"})
     */
    public function delete(Request $request, Consultation $consultation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $consultation->getId(), $request->request->get('_token'))) {
            $entityManager->remove($consultation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_consultation_index', [], Response::HTTP_SEE_OTHER);
    }

    private function getErrorsFromForm(FormInterface $form): array
    {
        $errors = array();
        foreach ($form->getErrors() as $error) {
            $errors[] = $error->getMessage();
        }
        foreach ($form->all() as $childForm) {
            if ($childForm instanceof FormInterface) {
                if ($childErrors = $this->getErrorsFromForm($childForm)) {
                    $errors[$childForm->getName()] = $childErrors;
                }
            }
        }
        return $errors;
    }
}
