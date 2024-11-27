<?php

namespace App\Http\Controllers;

use App\Traits\JsonRespond;
use Closure;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    use JsonRespond;

    protected int $limitPerPage = 10;

    public function __construct()
    {
        $this->middleware(function (Request $request, Closure $next) {
            if ($request->has('limit')) {
                if ($request->input('limit') > config('api.max_limit_per_page')) {
                    return $this->setHttpStatusCode(400)
                        ->setErrorCode(30)
                        ->respondWithError();
                }

                $this->setLimitPerPage($request->integer('limit'));
            }

            return $next($request);
        });
    }

    public function getLimitPerPage(): int
    {
        return $this->limitPerPage;
    }

    public function setLimitPerPage(int $limit): static
    {
        $this->limitPerPage = $limit;

        return $this;
    }
}
