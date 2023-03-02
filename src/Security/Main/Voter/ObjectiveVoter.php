<?php

namespace App\Security\Main\Voter;

use App\Entity\DailyReport;
use App\Entity\Objective\Objective;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class ObjectiveVoter extends Voter
{
    public const ACCESS = 'ACCESS';

    protected function supports(string $attribute, $subject): bool
    {
        return in_array($attribute, [self::ACCESS]) && $subject instanceof Objective;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof UserInterface) {
            return false;
        }

        /** @var Objective $subject */

        switch ($attribute) {
            case self::ACCESS:
                if ($subject->getClient()->getUser() === $user) return true;
        }

        throw new AccessDeniedException();
    }
}
