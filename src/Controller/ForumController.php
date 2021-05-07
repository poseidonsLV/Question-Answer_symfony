<?php

namespace App\Controller;

use App\Entity\Answers;
use App\Entity\Questions;
use App\Entity\QuestionViews;
use App\Entity\QuestionVotes;
use App\Repository\AnswersRepository;
use App\Repository\ContributorsRepository;
use App\Repository\FollowersRepository;
use App\Repository\QuestionsRepository;
use App\Repository\QuestionViewsRepository;
use App\Repository\QuestionVotesRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class ForumController extends AbstractController{

    /**
     * @Route("/forum", name="forum")
     */
    public function index(Request $request,ContributorsRepository $contributorsRepository,QuestionsRepository $questionsRepository): Response {
        
        $contributors = $contributorsRepository->findAll();
        $questions = $questionsRepository->findAll();

        if ($request->isMethod('POST') && $request->request->get('question-title')) {
            $this->redirectToRoute('forum');
            $title = $request->request->get('question-title');
            $questions = $questionsRepository->findQuestionByTitle($title);
        }

        return $this->render('forum/index.html.twig', ['contributors' => $contributors, 'questions' => $questions]);
    }


    /**
     * @Route("/forum/question/create", name="forum-question_create")
    */
    public function createQuestion(Request $request, UserInterface $userInterface, UserRepository $userRepository){

        if ($request->isMethod('POST')) {
            $title = $request->request->get('title');
            if (strlen($title) < 6) {
                $this->addFlash('error', 'Title is too short');
                return $this->redirectToRoute('forum-question_create');
            }
            $DEFAULT_VOTES = 0;
            $DEFAULT_VIEWS = 0;
            $DEFAULT_ANSWERS = 0;

            $user = $userRepository->find($userInterface->getId());

            $em = $this->getDoctrine()->getManager();

            $question = new Questions();

            $questionCount = $user->getQuestionsCount();
            $answeredCount = $user->getAnsweredCount();
            $newAnsweredCount = $answeredCount + 1;
            $newQuestionCount = $questionCount + 1;

            $question->setUid($user->getId());
            $question->setTitle($title);
            $question->setAnswered(false);
            $question->setViews($DEFAULT_VIEWS);
            $question->setVotes($DEFAULT_VOTES);
            $question->setAnswers($DEFAULT_ANSWERS);


            $user->setQuestionsCount($newQuestionCount);
            $user->setAnsweredCount($newAnsweredCount);

            $em->persist($question);
            $em->persist($user);

            $em->flush();


            return $this->redirectToRoute('forum');

        }

        return $this->render('forum/questions/create.html.twig');
    }

    /**
     * @Route("/forum/question/{qid}", name="forum-question_single-show")
     */

    public function singleQuestion(Request $request, $qid, QuestionViewsRepository $questionViewsRepository, QuestionsRepository $questionsRepository, AnswersRepository $answersRepository, UserInterface $userInterface, UserRepository $userRepository, QuestionVotesRepository $questionVotesRepository, FollowersRepository $followersRepository){

        $uid = $userInterface->getId();
        $question = $questionsRepository->find($qid);
        $answers = $answersRepository->findBy(['qid' => $qid]);
        $alreadyVoted = $questionVotesRepository->findBy(['uid' => $uid, 'qid' => $qid]);
        $alreadyViewed = $questionViewsRepository->findBy(['uid' => $uid, 'qid' => $qid]);
        $currViews = $question->getViews();
        $following = $followersRepository->findBy(['uid' => $uid, 'fid' => $question->getUid()]);

        if ($request->isMethod('POST') && $request->request->get('question-title')) {
            $this->redirectToRoute('forum');
            $title = $request->request->get('question-title');
            $questions = $questionsRepository->findQuestionByTitle($title);
        }
        foreach($answers as $answer) {
            $user = $userRepository->find($answer->getUid());
            $answer->user = ['username' => $user->getUsername(), 'avatar' => $user->getAvatar()];
        }

        if ($request->isMethod('POST')) {
            $this->answerQuestion($request, $userInterface, $userRepository,$questionsRepository, $qid);
            return $this->redirectToRoute('forum-question_single-show', ['qid' => $qid]);
        }
        
        if (!$alreadyViewed) {
            $qViews = new QuestionViews();
            $em = $this->getDoctrine()->getManager();

            $question->setViews($currViews + 1);

            $qViews->setUid($uid);
            $qViews->setQid($qid);

            $em->persist($question);
            $em->persist($qViews);
            $em->flush();
        }

        return $this->render('forum/questions/single.html.twig', ['question' => $question, 'answers' => $answers, 'voted' => $alreadyVoted, 'views' => $currViews, 'following' => $following]);
    }

    /**
     * @Route("/forum/questions/unanswered", name="show-unanswered_questions")
    */

    public function showUnansweredQuestions(Request $request, QuestionsRepository $questionsRepository, ContributorsRepository $contributorsRepository) {
        $unansweredQuestions = $questionsRepository->findBy(['answered' => 0]);
        $contributors = $contributorsRepository->findAll();

        return $this->render('forum/index.html.twig', ['contributors' => $contributors,'questions' => $unansweredQuestions]);

    }

    /**
     * @Route("/forum/questions/answered", name="show-answered_questions")
     */

    public function showAnsweredQuestions(Request $request, ContributorsRepository $contributorsRepository, QuestionsRepository $questionsRepository) {
        $em = $this->getDoctrine()->getManager();
        $connection = $em->getConnection();
        $statement = $connection->prepare("SELECT * FROM questions WHERE answered > 0");
        $statement->execute();
        $answeredQuestions = $statement->fetchAll();
        $contributors = $contributorsRepository->findAll();

        if ($request->isMethod('POST') && $request->request->get('question-title')) {
            $this->redirectToRoute('forum');
            $title = $request->request->get('question-title');
            $questions = $questionsRepository->findQuestionByTitle($title);
        }

        return $this->render('forum/index.html.twig', ['contributors' => $contributors,'questions' => $answeredQuestions]);

    }


    public function answerQuestion($request, $userInterface, $userRepository,$questionRepository, $qid){

            $description = $request->request->get('description');
            if (strlen($description) < 6) {
                $this->addFlash('error', 'Description is too short');
            }
            $user = $userRepository->find($userInterface->getId());
            $question = $questionRepository->find($qid);
            $date = date("F j, Y");
            $em = $this->getDoctrine()->getManager();

            $answer = new Answers();

            $questionAnswerCount = $question->getAnswers();

            $answer->setQid($qid);
            $answer->setUid($user->getId());
            $answer->setDescription($description);
            $answer->setDate($date);

            $question->setAnswers($questionAnswerCount + 1);

            $em->persist($answer);
            $em->persist($user);
            $em->persist($question);

            $em->flush();

        }

    
        /**
     * @Route("/forum/question/{qid}/vote", name="forum-question_single-vote")
     */

   
     public function questionVote($qid, UserInterface $user, QuestionsRepository $questionsRepository,QuestionVotesRepository $questionsVotesRepository){
        $uid = $user->getId();
        $alreadyVoted = $questionsVotesRepository->findBy(['uid' => $uid]);

//        Check if user have already voted, if yes, throw error, otherwise success message
        if ($alreadyVoted) {
            $this->addFlash('error', 'You have already voted');
            return $this->redirectToRoute('forum-question_single-show', ['qid' => $qid]);
        }
        $question = $questionsRepository->find($qid);

        if ($question->getUid() === $uid) {
            $this->addFlash('error', 'You cannot devote or vote your question');
            return $this->redirectToRoute('forum-question_single-show', ['qid' => $qid]);
        }

        $em = $this->getDoctrine()->getManager();

        $qVote = new QuestionVotes();

        $qVote->setQid($qid);
        $qVote->setUid($uid);


        $currVotes = $question->getVotes();
        $question->setVotes($currVotes + 1);

        $em->persist($qVote);
        $em->persist($question);
        $em->flush();

        $this->addFlash('success', 'Successfully voted question');
        return $this->redirectToRoute('forum-question_single-show', ['qid' => $qid]);

    }

    /**
     * @Route("/forum/question/{qid}/devote", name="forum-question_single-devote")
     */

    public function questionDevote($qid, UserInterface $user, QuestionsRepository $questionsRepository,QuestionVotesRepository $questionsVotesRepository){
        $uid = $user->getId();
        $alreadyVoted = $questionsVotesRepository->findBy(['uid' => $uid]);
        $question = $questionsRepository->find($qid);

        if ($question->getUid() === $uid) {
            $this->addFlash('error', 'You cannot devote or vote your question');
            return $this->redirectToRoute('forum-question_single-show', ['qid' => $qid]);
        }


//        Check if user have already voted, if yes, throw error, otherwise success message
        if ($alreadyVoted) {
            $em = $this->getDoctrine()->getManager();

            $currVotes = $question->getVotes();
            $question->setVotes($currVotes - 1);

            $em->remove($alreadyVoted[0]);
            $em->persist($question);

            $em->flush();

            $this->addFlash('success', 'Devoted the question');
            return $this->redirectToRoute('forum-question_single-show', ['qid' => $qid]);
        }
        $this->addFlash('error', 'You cant devote question if you are not voting');
        return $this->redirectToRoute('forum-question_single-show', ['qid' => $qid]);
    }

    /**
     * @Route("/forum/question/answered/{aid}", name="forum-question_single-answered")
     */

    public function setQuestionAnswered($aid, AnswersRepository $answersRepository, QuestionsRepository $questionsRepository){
        $answer = $answersRepository->find($aid);
        $question = $questionsRepository->find($answer->getQid());

        if ($question->getAnswered()) {
            $question->setAnswered(0);
            $flashMsg = 'Successfully unapproved the answer';
        } else {
            $flashMsg = 'Successfully approved the answer';
            $question->setAnswered($answer->getId());
        }


        $em = $this->getDoctrine()->getManager();

        $em->persist($question);
        $em->flush();

        $this->addFlash('success', $flashMsg);
        return $this->redirectToRoute('forum-question_single-show', ['qid' => $answer->getQid()]);

    }

}

