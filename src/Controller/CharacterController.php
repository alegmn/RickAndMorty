<?php
namespace App\Controller;

use App\Entity\Character;
use App\Repository\CharacterRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Hateoas\HateoasBuilder;
use Hateoas\Representation\PaginatedRepresentation;
use Hateoas\Representation\CollectionRepresentation;
use JMS\Serializer\SerializerInterface; 
use JMS\Serializer\SerializationContext;

/**
 * Class CharacterController
 * @package App\Controller
 *
 * @Route(path="/api/v1/")
 */
class CharacterController extends AbstractController
{
    private $serializer;
    
    public function __consttruct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }
    /**
     * @Route("characters", name="add_character", methods={"POST"})
     */
    public function add(ManagerRegistry $doctrine, Request $request): Response
    {
        try {
            $data = json_decode($request->getContent(), false);
		    $repository = $doctrine->getRepository(Character::class);

            $name = $data->name;
            $lastName = $data->lastName;
            $age = $data->age;
            $isProtagonist = $data->isProtagonist;
            $occupation = $data->occupation;
            $gender = $data->gender;

            if (empty($name) || empty($lastName) || 
                empty($age) || empty($isProtagonist) || 
                empty($occupation) || empty($gender)) {

                throw new NotFoundHttpException('Expecting mandatory parameters!');
            }

            $newCharacter = $repository->save($name, $lastName, $age, $isProtagonist, $occupation, $gender);
            $hateoas = HateoasBuilder::create()->build();
            $json = $hateoas->serialize(['ok'=>true, 'data' => $newCharacter, 'msg' => 'Character added'], 'json');
            $response = $this->jsonResponse($json, 201);

        } catch(\Exception $e) {
            $json = json_encode(["ok" => false, "msg" => $e->getMessage()]);
            $response = $this->jsonResponse($json, 500);
        }
        return $response;
    }

    /**
     * @Route("characters", name="get_all_characters", methods={"GET"})
     */
    public function getAll(ManagerRegistry $doctrine, Request $request, SerializerInterface $serializer): Response
	{
        try {
		    $repository = $doctrine->getRepository(Character::class);
            $array_data = json_decode($request->getContent(), true);

            $page = empty($array_data['page']) ? 1: (int)$array_data['page'];
            $limit = empty($array_data['limit']) ? 2: (int)$array_data['limit'];
            $name = empty($array_data['name']) ? '': $array_data['name'];
            $gender = empty($array_data['gender']) ? '': $array_data['gender'];

            $paginatedCollection = $this->getPaginatedCollection($repository, $name, $gender, $page, $limit);

            $json = $serializer->serialize($paginatedCollection, 'json');
            $response = $this->jsonResponse($json, 201);

        } catch(\Exception $e) {
            $json = json_encode(["ok" => false, "msg" => $e->getMessage()]);
            $response = $this->jsonResponse($json, 500);
        }
        return $response;
	}

    /**
     * @Route("characters/{id}", name="get_one_character", methods={"GET"})
     */
    public function getById(ManagerRegistry $doctrine, $id): Response
    {
        try {
		    $repository = $doctrine->getRepository(Character::class);
            $character = $repository->findOneBy(['id' => $id]);
            if (!$character) {
              throw $this->createNotFoundException('No news found for id ' . $id);
            }

            $hateoas = HateoasBuilder::create()->build();
            $json = $hateoas->serialize(['ok'=>true, 'data' => $character, 'msg' => 'Character Ok'], 'json');
            $response = $this->jsonResponse($json, 201);
        } catch(\Exception $e) {
            $json = json_encode(["ok" => false, "msg" => $e->getMessage()]);
            $response = $this->jsonResponse($json, 500);
        }
        return $response;
    }

    /**
     * @Route("characters/{id}", name="update_character", methods={"PUT"})
     */
    public function updateCharacter(ManagerRegistry $doctrine, $id, Request $request): Response
    {
        try {
		    $repository = $doctrine->getRepository(Character::class);
            $character = $repository->findOneBy(['id' => $id]);
            if (!$character) {
              throw $this->createNotFoundException('No news found for id ' . $id);
            }

            $array_data = (array)json_decode($request->getContent(), true);
            $character = $repository->update($character, $array_data);
            $hateoas = HateoasBuilder::create()->build();
            $json = $hateoas->serialize(['ok'=>true, 'data' => $character, 'msg' => 'Character Updated'], 'json');
            $response = $this->jsonResponse($json, 201);
            return $response;

        } catch(\Exception $e) {
            $json = json_encode(["ok" => false, "msg" => $e->getMessage()]);
            $response = $this->jsonResponse($json, 500);
        }

        return $response;
    }

    /**
     * @Route("characters/{id}", name="delete_one_character", methods={"DELETE"})
     */
    public function deleteCharacter(ManagerRegistry $doctrine, Int $id): Response
    {
        try {
		    $repository = $doctrine->getRepository(Character::class);
            $character = $repository->findOneBy(['id' => $id]);

            if (!$character) {
              throw $this->createNotFoundException('No news found for id ' . $id);
            }

            $repository->remove($character);

            $hateoas = HateoasBuilder::create()->build();
            $json = $hateoas->serialize(['ok'=>true, 'data' => $character, 'msg' => 'Character Deleted'], 'json');
            $response = $this->jsonResponse($json, 201);

        } catch(\Exception $e) {
            $json = json_encode(["ok" => false, "msg" => $e->getMessage()]);
            $response = $this->jsonResponse($json, 500);
        }
        return $response;
    }

    private function jsonResponse(String $json, Int $code): Response {
        $response = new Response();
        $response->setContent($json);
        $response->setStatusCode($code);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    private function getPaginatedCollection(CharacterRepository $repository, String $name, String $gender, Int $page, Int $limit): PaginatedRepresentation{
        $result = $repository->findAllPagination($name, $gender, $page, $limit);
        $totalPages = empty($result["totalPages"]) ? 0 : $result["totalPages"];
        $characters = empty($result["paginator"]) ? [] : $result["paginator"];

        return new PaginatedRepresentation(
            new CollectionRepresentation($characters, 'characters', 'characters'),
            'get_all_characters',
            array(),
            $page,
            $limit,
            $totalPages,
            'page', 'limit', false
        );
    }
}

?>
