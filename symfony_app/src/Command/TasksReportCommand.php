<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Repository\UserRepository;
use App\Repository\TaskRepository;

#[AsCommand(
    name: 'app:tasks:report',
    description: 'Tasks count as per status for each users,',
)]
class TasksReportCommand extends Command
{
    private UserRepository $userRepository;
    private TaskRepository $taskRepository;
    public function __construct(
        UserRepository $userRepository, TaskRepository $taskRepository
    )
    {
        parent::__construct();
        $this->userRepository = $userRepository;
        $this->taskRepository = $taskRepository;
    }

    protected function configure(): void
    {
        $this->addOption('user', null, InputOption::VALUE_REQUIRED, 'Email of the users to generate report for');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $statuses = ['todo', 'in_progress', 'done'];
        $email = $input->getOption('user');
        if ($email) {
            // Single users mode
            $user = $this->userRepository->findOneBy(['email' => $email]);

            if (!$user) {
                $io->error("User with email '$email' not found.");
                return Command::FAILURE;
            }

            $io->title("Tasks Report for " . $user->getEmail());
            foreach ($statuses as $status) {
                $count = $this->taskRepository->count([
                    'user' => $user,
                    'status' => $status,
                ]);
                $io->writeln(sprintf("  - %s: %d", ucfirst($status), $count));
            }
        }
        else {
            // All users mode
            $io->title('Tasks Report for All Users');
            $users = $this->userRepository->findAll();

            foreach ($users as $user) {
                $io->section("User: " . $user->getEmail());
                foreach ($statuses as $status) {
                    $count = $this->taskRepository->count([
                        'user' => $user,
                        'status' => $status,
                    ]);
                    $io->writeln(sprintf("  - %s: %d", ucfirst($status), $count));
                }
            }
        }

        $io->success('Report generated successfully.');
        return Command::SUCCESS;
    }
}
