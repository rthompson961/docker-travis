<?php

namespace App\MessageHandler;

use App\Message\FruitMessage;
use App\Repository\FruitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Workflow\WorkflowInterface;

class FruitMessageHandler implements MessageHandlerInterface
{
    private $entityManager;
    private $fruitRepository;
    private $bus;
    private $workflow;

    public function __construct(
        EntityManagerInterface $entityManager,
        FruitRepository $fruitRepository,
        MessageBusInterface $bus,
        WorkflowInterface $fruitStateMachine
    ) {
        $this->entityManager = $entityManager;
        $this->fruitRepository = $fruitRepository;
        $this->bus = $bus;
        $this->workflow = $fruitStateMachine;
    }

    public function __invoke(FruitMessage $message)
    {
        $fruit = $this->fruitRepository->find($message->getId());
        if (!$fruit) {
            return;
        }

        if ($this->workflow->can($fruit, 'pass')) {
            if ($fruit->getName() === 'banana') {
                $transition = 'fail';
            } else {
                $transition = 'pass';
            }

            $this->workflow->apply($fruit, $transition);
            $this->entityManager->flush();
            $this->bus->dispatch($message);
        } 

        $this->entityManager->flush();
    }
}
