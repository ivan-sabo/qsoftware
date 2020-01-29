<?php


namespace App\Command;


use App\Service\QSSClientService;
use App\Service\UserService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class CreateAuthorCommand extends Command
{
    const AUTHOR_URL = '/api/authors';

    protected static $defaultName = 'app:create-author';

    /**
     * @var UserService
     */
    private $userService;

    /**
     * @var QSSClientService
     */
    private $qssClientService;

    /**
     * CreateAuthorCommand constructor.
     * @param UserService $userService
     * @param string|null $name
     */
    public function __construct(UserService $userService, QSSClientService $QSSClientService, string $name = null)
    {
        $this->userService = $userService;
        $this->qssClientService = $QSSClientService;

        parent::__construct($name);
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');

        $question = new Question('Api user email:');
        $email = $helper->ask($input, $output, $question);

        $question = new Question('Api user password:');
        $password = $helper->ask($input, $output, $question);

        $token = $this->userService->getTokenForUser($email, $password);

        if (empty($token)) {
            $output->writeln('Api user token couldn\'t be retrived');

            return 0;
        }

        $question = new Question('First name:');
        $firstName = $helper->ask($input, $output, $question);

        $question = new Question('Last name:');
        $lastName = $helper->ask($input, $output, $question);

        $question = new Question('Birthday:');
        $birthday = $helper->ask($input, $output, $question);

        $question = new Question('Gender:');
        $gender = $helper->ask($input, $output, $question);

        $question = new Question('Place of birth:');
        $placeOfBirth = $helper->ask($input, $output, $question);

        $question = new Question('Biography:');
        $biography = $helper->ask($input, $output, $question);

        $body = [
            'first_name' => $firstName,
            'last_name' => $lastName,
            'birthday' => $birthday,
            'biography' => $biography,
            'gender' => $gender,
            'place_of_birth' => $placeOfBirth
        ];

        $response = $this->qssClientService->sentPostRequestWithToken(self::AUTHOR_URL, $body, $token);

        if (empty($response)) {
            $output->writeln('Author couldn\'t be created');

            return 0;
        }

        $output->writeln('Author successfully created!');

        return 1;
    }
}
