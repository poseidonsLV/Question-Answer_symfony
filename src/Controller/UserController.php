<?php

namespace App\Controller;

use App\Entity\Followers;
use App\Entity\User;
use App\Repository\FollowersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class UserController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response {
         if ($this->getUser()) {
            dump('logged in!'); die;
         }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/register", name="app_register")
    */

    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response {
        if ($request->isMethod('POST')) {
            $user = new User();
            $username = $request->get('username');
            $password = $request->get('password');
            $avatar = $request->files->get('avatar');
            $repeatedPassword = $request->get('repeat-password');
            $status = $this->checkCredentials($password,$repeatedPassword, $username);

            if ($avatar === false) {
                $this->addFlash('error', 'Something went wrong when uploading avatar');
            }

            $filename = md5(uniqid()) . '.' . $avatar->guessClientExtension();
            $avatar->move(
                $this->getParameter('uploads_dir'),
                $filename
            );

            if ($status === true) {
//                encode password
                $encodedPsw = $passwordEncoder->encodePassword($user, $password);

//                Set data which we will send to DB
                $user->setUsername($username);
                $user->setPassword($encodedPsw);
                $user->setLevel(1);
                $user->setAvatar($filename);
                $user->setAdmin(0);
                $user->setModerator(0);
                $user->setQuestionsCount(0);
                $user->setAnsweredCount(0);

//                Send data to DB
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();

//                redirect to login page after successful register
                return $this->redirectToRoute('app_login');
            }

        };

        return $this->render('security/register.html.twig');
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    protected function checkCredentials($password, $repeatedPassword, $username) {
        $err = array();
        if ($password !== $repeatedPassword) {
            $this->addFlash('error', 'Passwords does not match');
            array_push($err, 'error');
        }
        if (strlen($password) < 8) {
            $this->addFlash('error', 'Password too short');
            array_push($err, 'error');
        }
        if (strlen($username) === 0) {
            $this->addFlash('error', 'Username can not be blank');
            array_push($err, 'error');
        }
        if (count($err) > 0) {
            return false;
        }  else {
            return true;
        }
    }

    /**
     * @Route("/forum/user/{uid}/follow/{qid}", name="follow-user")
     */

    public function followUser(Request $request, UserInterface $user, FollowersRepository $followersRepository){
        $qid = $request->get('qid');
        $fid = (int)$request->get('uid');
        $uid = (int)$user->getId();
        if ($uid === $fid) {
            $this->addFlash('error', 'You cant unfollow or follow yourself, dont try or will get banned');
            return $this->redirectToRoute('forum-question_single-show', ['qid' => $qid]);
        }

        $currUserFollowers = $user->getFollowerCount();

        $following = $followersRepository->findBy(['uid' => $uid, 'fid' => $fid]);

        if ($following) {
            $this->addFlash('error', 'You are already following this user');
            return $this->redirectToRoute('forum-question_single-show', ['qid' => $qid]);
        }

        $follower = new Followers();

        $user->setFollowerCount($currUserFollowers + 1);

        $follower->setUid($uid);
        $follower->setFid($fid);

        $em = $this->getDoctrine()->getManager();
        $em->persist($follower);
        $em->persist($user);
        $em->flush();

        if ($qid) {
            $this->addFlash('success', 'Started following this user');
            return $this->redirectToRoute('forum-question_single-show', ['qid' => $qid]);
        }

    }

    /**
     * @Route("/forum/user/{uid}/unfollow/{qid}/", name="unfollow-user")
     */

    public function unfollowUser(Request $request, UserInterface $user, FollowersRepository $followersRepository){
        $qid = $request->get('qid');
        $fid = (int)$request->get('uid');
        $uid = (int)$user->getId();

        if ($uid === $fid) {
            $this->addFlash('error', 'You cant unfollow or follow yourself, dont try or will get banned');
            return $this->redirectToRoute('forum-question_single-show', ['qid' => $qid]);
        }

        $currUserFollowers = $user->getFollowerCount();

        $follower = $followersRepository->findOneBy(['uid' => $uid, 'fid' => $fid]);

        if ($follower) {
            $em = $this->getDoctrine()->getManager();

            $user->setFollowerCount($currUserFollowers - 1);

            $em->remove($follower);
            $em->persist($user);
            $em->flush();
            $this->addFlash('success', 'Unfollowed this user');
            return $this->redirectToRoute('forum-question_single-show', ['qid' => $qid]);
        }


        $this->addFlash('error', 'You are not following this user');
        return $this->redirectToRoute('forum-question_single-show', ['qid' => $qid]);

    }

}
