<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Csrf\Exception\InvalidCsrfTokenException;

class YourController extends AbstractController
{
    public function yourAction(Request $request, CsrfTokenManagerInterface $csrfTokenManager)
    {
        // Validate token
        $submittedToken = $request->request->get('_token');
        if (!$this->isCsrfTokenValid('your_intention_here', $submittedToken)) {
            throw new InvalidCsrfTokenException('Invalid CSRF token');
        }
        
        // ... rest of your code
    }
} 