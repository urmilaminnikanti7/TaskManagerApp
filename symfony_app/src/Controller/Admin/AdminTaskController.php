<?php
namespace App\Controller\Admin;

use App\Entity\Task;
use App\Form\TaskType;
use Doctrine\ORM\EntityManagerInterface; // ✅ Make sure this is imported
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/tasks')]
class AdminTaskController extends AbstractController
{
    #[Route('/', name: 'admin_tasks')]
    public function index(EntityManagerInterface $em): Response
    {
        $tasks = $em->getRepository(Task::class)->findAll();
        return $this->render('admin/tasks/index.html.twig', [
            'tasks' => $tasks,
        ]);
    }

    #[Route('/create', name: 'admin_task_create')]
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($task);
            $em->flush();
            $this->addFlash('success', 'Task created successfully.');
            return $this->redirectToRoute('admin_tasks');
        }

        return $this->render('admin/tasks/form.html.twig', [
            'form' => $form->createView(),
            'title' => 'Create Task',
        ]);
    }

    #[Route('/edit/{id}', name: 'admin_task_edit')]
    public function edit(Task $task, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Task updated successfully.');
            return $this->redirectToRoute('admin_tasks');
        }

        return $this->render('admin/tasks/form.html.twig', [
            'form' => $form->createView(),
            'title' => 'Edit Task',
        ]);
    }

    #[Route('/delete/{id}', name: 'admin_task_delete', methods: ['POST'])]
    public function delete(Task $task, Request $request, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete'.$task->getId(), $request->request->get('_token'))) {
            $em->remove($task);
            $em->flush();
            $this->addFlash('success', 'Task deleted successfully.');
        }

        return $this->redirectToRoute('admin_tasks');
    }
}
