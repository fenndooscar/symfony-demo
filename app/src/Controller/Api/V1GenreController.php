<?php

declare(strict_types = 1);

namespace App\Controller\Api;

use App\Http\Response\JsonResponder;
use App\OpenApi\V1 as ApiDoc;
use App\State\Command\Genre\CreateManyGenresCommand;
use App\State\Command\Genre\DeleteManyGenresCommand;
use App\State\Command\Genre\UpdateManyGenresCommand;
use App\State\Query\Genre\ListGenresQuery;
use App\State\Query\Genre\ShowGenreQuery;
use App\State\State;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

use function array_merge;

#[AsController]
#[Route('/api/v1/genres', name: 'api_v1_genres_')]
#[OA\Tag('Genre')]
readonly class V1GenreController
{
    public function __construct(
        private JsonResponder $responder,
        private State $state
    ) {
    }

    /**
     * Show one book by id.
     *
     * @throws Throwable
     */
    #[Route(
        path: '/show/{id}',
        name: 'show_one',
        requirements: [
            'id' => '\d+',
        ],
        methods: [Request::METHOD_GET],
    )]
    public function showOne(Request $request): Response
    {
        $data = $this->state->query(ShowGenreQuery::fromArray(
            array_merge(
                $request->query->all(),
                $request->attributes->all()
            )
        ));

        return $this->responder->okOne($data);
    }

    /**
     * List of books.
     *
     * @throws Throwable
     */
    #[OA\QueryParameter(name: 'limit', in: 'query', example: 10)]
    #[OA\QueryParameter(name: 'offset', in: 'query', example: 0)]
    #[Route(path: '/list', name: 'list_many', methods: [Request::METHOD_GET])]
    public function listMany(Request $request, int $limit, int $offset): Response
    {
        $data = $this->state->query(ListGenresQuery::fromArray($request->query->all()));

        return $this->responder->okMany($data['genres'], $data['total'], $limit, $offset);
    }

    /**
     * Create a books.
     *
     * @throws Throwable
     */
    #[OA\RequestBody(content: new OA\JsonContent(
        ref: new Model(type: CreateManyGenresCommand::class)
    ))]
    #[ApiDoc\Response\Created\ManySuccess]
    #[ApiDoc\Response\InternalError]
    #[ApiDoc\Response\BadRequest]
    #[Route(
        path: '/create',
        name: 'create_many',
        methods: [Request::METHOD_POST]
    )]
    public function createMany(Request $request): Response
    {
        $data = $this->state->command(CreateManyGenresCommand::fromArray($request->toArray()));

        return $this->responder->createdMany($data);
    }

    /**
     * Update a books.
     *
     * @throws Throwable
     */
    #[OA\RequestBody(content: new OA\JsonContent(
        ref: new Model(type: UpdateManyGenresCommand::class)
    ))]
    #[ApiDoc\Response\Updated\ManySuccess]
    #[ApiDoc\Response\NotFound]
    #[ApiDoc\Response\InternalError]
    #[ApiDoc\Response\BadRequest]
    #[Route(
        path: '/update',
        name: 'update_many',
        methods: [Request::METHOD_PATCH]
    )]
    public function updateMany(Request $request): Response
    {
        $data = $this->state->command(UpdateManyGenresCommand::fromArray($request->toArray()));

        return $this->responder->updatedMany($data);
    }

    /**
     * Delete a books.
     *
     * @throws Throwable
     */
    #[OA\RequestBody(content: new OA\JsonContent(
        ref: new Model(type: DeleteManyGenresCommand::class)
    ))]
    #[ApiDoc\Response\Deleted\ManySuccess]
    #[ApiDoc\Response\InternalError]
    #[ApiDoc\Response\BadRequest]
    #[Route(
        path: '/delete',
        name: 'delete_many',
        methods: [Request::METHOD_DELETE]
    )]
    public function deleteMany(Request $request): Response
    {
        $this->state->command(DeleteManyGenresCommand::fromArray($request->toArray()));

        return $this->responder->noContent();
    }
}
