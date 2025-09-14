<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\File;

class DocumentationController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/documentation.json",
     *     summary="Get API documentation",
     *     tags={"Documentation"},
     *     @OA\Response(
     *         response=200,
     *         description="API documentation in OpenAPI format"
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        $docs = config('api-docs');
        
        // Add paths from controllers
        $docs['paths'] = $this->getPathsFromControllers();
        
        return response()->json($docs);
    }
    
    /**
     * Get all API paths from controller docblocks
     */
    protected function getPathsFromControllers(): array
    {
        $paths = [];
        $controllers = [
            'App\Http\Controllers\API\PaymentController',
            // Add other API controllers here
        ];
        
        foreach ($controllers as $controller) {
            $reflection = new \ReflectionClass($controller);
            $methods = $reflection->getMethods(\ReflectionMethod::IS_PUBLIC);
            
            foreach ($methods as $method) {
                $docComment = $method->getDocComment();
                if ($docComment) {
                    $openApi = $this->parseOpenApiDocBlock($docComment);
                    if (!empty($openApi)) {
                        $paths = array_merge($paths, $openApi);
                    }
                }
            }
        }
        
        return $paths;
    }
    
    /**
     * Parse OpenAPI annotations from docblock
     */
    protected function parseOpenApiDocBlock(string $docComment): array
    {
        $openApi = [];
        
        // This is a simplified parser. In a real application, you might want to use a library
        // like zircote/swagger-php to parse the annotations properly.
        
        if (preg_match('/@OA\\\([\s\S]*?\*\/)/', $docComment, $matches)) {
            $json = str_replace(['@OA\', '*/'], ['', ''], $matches[0]);
            $json = preg_replace('/\*\s*/', '', $json);
            $json = trim($json);
            
            try {
                $data = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
                if (isset($data['path'])) {
                    $openApi[$data['path']][strtolower($data['method'] ?? 'get')] = $data;
                }
            } catch (\JsonException $e) {
                // Handle JSON parse error
                \Log::error('Failed to parse OpenAPI docblock: ' . $e->getMessage());
            }
        }
        
        return $openApi;
    }
}
