<?php

namespace App\Http\Middleware;

use App\Services\ActivityLogService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LogActivity
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Only log for authenticated users and non-API routes
        if (auth()->check() && !$request->is('api/*')) {
            $this->logRequest($request, $response);
        }

        return $response;
    }

    /**
     * Log the request activity
     */
    private function logRequest(Request $request, Response $response): void
    {
        $method = $request->method();
        $url = $request->fullUrl();
        $route = $request->route();

        // Skip logging for certain routes
        if ($this->shouldSkipLogging($request)) {
            return;
        }

        $description = $this->getDescription($request, $route);
        $logName = $this->getLogName($request, $route);
        $event = $this->getEvent($method, $route);
        $logLevel = $this->getLogLevel($response);

        ActivityLogService::log(
            $description,
            null,
            auth()->user(),
            $event,
            $logName,
            [
                'route' => $route ? $route->getName() : null,
                'controller' => $route ? $route->getActionName() : null,
                'status_code' => $response->getStatusCode(),
                'response_time' => microtime(true) - LARAVEL_START
            ],
            $logLevel
        );
    }

    /**
     * Check if we should skip logging for this request
     */
    private function shouldSkipLogging(Request $request): bool
    {
        $skipRoutes = [
            'admin.activity-logs.*',
            'admin.dashboard',
            'admin.logout'
        ];

        $skipPaths = [
            '/admin/activity-logs',
            '/admin/dashboard',
            '/admin/logout'
        ];

        $routeName = $request->route() ? $request->route()->getName() : null;
        $path = $request->path();

        // Skip if route name matches skip patterns
        if ($routeName) {
            foreach ($skipRoutes as $pattern) {
                if (fnmatch($pattern, $routeName)) {
                    return true;
                }
            }
        }

        // Skip if path matches skip patterns
        foreach ($skipPaths as $skipPath) {
            if (str_starts_with($path, $skipPath)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get description for the activity
     */
    private function getDescription(Request $request, $route): string
    {
        $method = $request->method();
        $routeName = $route ? $route->getName() : null;

        // Custom descriptions for specific routes
        $descriptions = [
            'admin.products.index' => 'Viewed products list',
            'admin.products.create' => 'Viewed create product form',
            'admin.products.store' => 'Created new product',
            'admin.products.edit' => 'Viewed edit product form',
            'admin.products.update' => 'Updated product',
            'admin.products.destroy' => 'Deleted product',
            'admin.orders.index' => 'Viewed orders list',
            'admin.orders.show' => 'Viewed order details',
            'admin.orders.update' => 'Updated order',
            'admin.customers.index' => 'Viewed customers list',
            'admin.customers.show' => 'Viewed customer details',
        ];

        if ($routeName && isset($descriptions[$routeName])) {
            return $descriptions[$routeName];
        }

        // Generic descriptions based on HTTP method
        return match ($method) {
            'GET' => 'Viewed page',
            'POST' => 'Created resource',
            'PUT', 'PATCH' => 'Updated resource',
            'DELETE' => 'Deleted resource',
            default => 'Performed action'
        };
    }

    /**
     * Get log name for the activity
     */
    private function getLogName(Request $request, $route): string
    {
        $routeName = $route ? $route->getName() : null;

        if ($routeName) {
            $parts = explode('.', $routeName);
            if (count($parts) >= 2) {
                return $parts[1]; // e.g., 'products', 'orders', 'customers'
            }
        }

        return 'default';
    }

    /**
     * Get event type for the activity
     */
    private function getEvent(string $method, $route): string
    {
        $routeName = $route ? $route->getName() : null;

        if ($routeName) {
            $parts = explode('.', $routeName);
            if (count($parts) >= 3) {
                return $parts[2]; // e.g., 'index', 'create', 'store', 'edit', 'update', 'destroy'
            }
        }

        return match ($method) {
            'GET' => 'view',
            'POST' => 'create',
            'PUT', 'PATCH' => 'update',
            'DELETE' => 'delete',
            default => 'action'
        };
    }

    /**
     * Get log level based on response
     */
    private function getLogLevel(Response $response): string
    {
        $statusCode = $response->getStatusCode();

        return match (true) {
            $statusCode >= 500 => 'error',
            $statusCode >= 400 => 'warning',
            default => 'info'
        };
    }
}
