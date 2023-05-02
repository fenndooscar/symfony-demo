<?php

declare(strict_types = 1);

namespace App\Controller\Api;

use App\Entity\Author;
use App\Http\Response\JsonResponder;
use App\OpenApi\V1 as ApiDoc;
use App\State\Command\Author\CreateManyAuthorsCommand;
use App\State\Command\Author\DeleteManyAuthorsCommand;
use App\State\Command\Author\UpdateManyAuthorsCommand;
use App\State\Query\Author\ListAuthorsQuery;
use App\State\Query\Author\ShowAuthorQuery;
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
#[Route('/api/v1/authors', name: 'api_v1_authors')]
#[OA\Tag('Authors')]
readonly class V1AuthorController
{
    public function __construct(
        private JsonResponder $responder,
        private State $state
    ) {
    }

    /**
     * Show one author by id.
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
    #[ApiDoc\Response\ShowOne(Author::class)]
    public function showOne(Request $request): Response
    {
        $data = $this->state->query(ShowAuthorQuery::fromArray(
            array_merge(
                $request->query->all(),
                $request->attributes->all()
            )
        ));

        return $this->responder->okOne($data);
    }

    /**
     * List of authors.
     *
     * @throws Throwable
     */
    #[OA\QueryParameter(name: 'limit', in: 'query', example: 10)]
    #[OA\QueryParameter(name: 'offset', in: 'query', example: 0)]
    #[Route(path: '/list', name: 'list_many', methods: [Request::METHOD_GET])]
    #[ApiDoc\Response\ListMany(Author::class)]
    public function listMany(Request $request): Response
    {
        $query = ListAuthorsQuery::fromArray($request->query->all());
        $data  = $this->state->query($query);

        return $this->responder->okMany($data['authors'], $data['total'], $query->limit, $query->offset);
    }

    /**
     * Create an authors.
     *
     * @throws Throwable
     */
    #[OA\RequestBody(content: new OA\JsonContent(
        ref: new Model(type: CreateManyAuthorsCommand::class)
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
        $data = $this->state->command(CreateManyAuthorsCommand::fromArray($request->toArray()));

        return $this->responder->createdMany($data);
    }

    /**
     * Update an authors.
     *
     * @throws Throwable
     */
    #[OA\RequestBody(content: new OA\JsonContent(
        ref: new Model(type: UpdateManyAuthorsCommand::class)
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
        $data = $this->state->command(UpdateManyAuthorsCommand::fromArray($request->toArray()));

        return $this->responder->updatedMany($data);
    }

    /**
     * Delete an authors.
     *
     * @throws Throwable
     */
    #[OA\RequestBody(content: new OA\JsonContent(
        ref: new Model(type: DeleteManyAuthorsCommand::class)
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
        $this->state->command(DeleteManyAuthorsCommand::fromArray($request->toArray()));

        return $this->responder->noContent();
    }
}
