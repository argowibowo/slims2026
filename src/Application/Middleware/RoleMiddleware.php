<?php

namespace App\Application\Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface as Middleware;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class RoleMiddleware implements Middleware
{
    private $requiredRole;

    public function __construct(string $role)
    {
        $this->requiredRole = $role;
    }

    public function process(Request $request, RequestHandler $handler): Response
    {
        // Ambil payload JWT yang sudah di-decode oleh JwtAuthentication Middleware
        $token = $request->getAttribute('token');

        // Ekstrak role dari token (menangani array maupun object)
        $userRole = is_array($token) ? ($token['role'] ?? '') : ($token->role ?? '');

        if ($userRole !== $this->requiredRole) {
            // Jika role tidak cocok, kirim 403 Forbidden
            $response = new \Slim\Psr7\Response();
            $response->getBody()->write(json_encode([
                "status" => "error",
                "message" => "Forbidden: Anda tidak memiliki akses (Dibutuhkan role: {$this->requiredRole})"
            ]));
            return $response->withStatus(403)->withHeader('Content-Type', 'application/json');
        }

        // Lanjut ke proses berikutnya jika role sesuai
        return $handler->handle($request);
    }
}
