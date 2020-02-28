<?php

use App\Entity\MicroPost;
use Symfony\Component\Workflow\Exception\LogicException;

$post = new MicroPost();

$workflow = $this->container->get('workflow.blog_publishing');
$workflow->can($post, 'publish'); // False
$workflow->can($post, 'to_review'); // True

// Update the currentState on the post
try {
    $workflow->apply($post, 'to_review');
} catch (LogicException $exception) {
    // ...
}

// See all the available transitions for the post in the current state
$transitions = $workflow->getEnabledTransitions($post);