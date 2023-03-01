<?php

namespace App\Security\Main\Voter;

use App\Entity\Activity;
use App\Entity\DailyReport;
use App\Entity\Food;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class ActivityVoter extends Voter
{
    public const ACCESS = 'ACCESS';

    protected function supports(string $attribute, $subject): bool
    {
        return in_array($attribute, [self::ACCESS]) && $subject instanceof Activity;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof UserInterface) {
            return false;
        }

        /** @var Activity $subject */

        switch ($attribute) {
            case self::ACCESS:
                if ($subject->getClient()->getUser() === $user) return true;
        }

        throw new AccessDeniedException();
    }
}
