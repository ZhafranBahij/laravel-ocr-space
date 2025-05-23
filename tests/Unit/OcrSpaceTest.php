<?php

use Codesmiths\LaravelOcrSpace\Exceptions\MissingOcrSpaceOptionException;

it('parses an image file', function (): void {
    $filePath = __DIR__.'/../Fixtures/test-image.png';
    $service = new \Codesmiths\LaravelOcrSpace\OcrSpace;
    $options = new \Codesmiths\LaravelOcrSpace\OcrSpaceOptions;

    $response = $service->parseImageFile(
        filePath: $filePath,
        options: $options,
    );

    expect($response)->toBeInstanceOf(\Codesmiths\LaravelOcrSpace\ValueObjects\OcrSpaceResponse::class)
        ->and($response->hasError())->toBeFalse()
        ->and($response->getParsedResults())->count(1)
        ->and($response->getParsedResults()[0])->toBeInstanceOf(\Codesmiths\LaravelOcrSpace\ValueObjects\ParsedResult::class);
});

it('parses a binary image', function (): void {
    $filePath = __DIR__.'/../Fixtures/test-image.png';
    $service = new \Codesmiths\LaravelOcrSpace\OcrSpace;
    $options = new \Codesmiths\LaravelOcrSpace\OcrSpaceOptions;
    $options = $options->fileType('image/png');

    $response = $service->parseBinaryImage(
        file_get_contents($filePath),
        $options,
    );

    expect($response)->toBeInstanceOf(\Codesmiths\LaravelOcrSpace\ValueObjects\OcrSpaceResponse::class)
        ->and($response->hasError())->toBeFalse()
        ->and($response->getParsedResults())->count(1);
});

it('throws a MissingOcrSpaceOptionException if the fileType option is missing when parsing a binary image', function () {
    $filePath = __DIR__.'/../Fixtures/test-image.png';
    $service = new \Codesmiths\LaravelOcrSpace\OcrSpace;
    $options = new \Codesmiths\LaravelOcrSpace\OcrSpaceOptions;

    expect(fn () => $service->parseBinaryImage(
        file_get_contents($filePath),
        $options,
    ))->toThrow(MissingOcrSpaceOptionException::class, 'The [fileType] property is required in the OcrSpaceOptions when parsing binary images.');
});

it('parses a base64 image', function (): void {
    $filePath = __DIR__.'/../Fixtures/test-image.png';
    $service = new \Codesmiths\LaravelOcrSpace\OcrSpace;
    $options = new \Codesmiths\LaravelOcrSpace\OcrSpaceOptions;
    $options = $options->fileType('image/png');

    $response = $service->parseBase64Image(
        base64_encode(file_get_contents($filePath)),
        $options,
    );

    expect($response)->toBeInstanceOf(\Codesmiths\LaravelOcrSpace\ValueObjects\OcrSpaceResponse::class)
        ->and($response->hasError())->toBeFalse()
        ->and($response->getParsedResults())->count(1);
});

it('throws a MissingOcrSpaceOptionException if the fileType option is missing when parsing a base64 image', function () {
    $filePath = __DIR__.'/../Fixtures/test-image.png';
    $service = new \Codesmiths\LaravelOcrSpace\OcrSpace;
    $options = new \Codesmiths\LaravelOcrSpace\OcrSpaceOptions;

    expect(fn () => $service->parseBase64Image(
        base64_encode(file_get_contents($filePath)),
        $options,
    ))->toThrow(MissingOcrSpaceOptionException::class, 'The [fileType] property is required in the OcrSpaceOptions when parsing base64 images.');
});

it('parses an image url', function (): void {
    $service = new \Codesmiths\LaravelOcrSpace\OcrSpace;
    $options = new \Codesmiths\LaravelOcrSpace\OcrSpaceOptions;

    $response = $service->parseImageUrl(
        'https://raw.githubusercontent.com/cdsmths/laravel-ocr-space/refs/heads/main/tests/Fixtures/test-image.png',
        $options,
    );

    expect($response)->toBeInstanceOf(\Codesmiths\LaravelOcrSpace\ValueObjects\OcrSpaceResponse::class)
        ->and($response->hasError())->toBeFalse()
        ->and($response->getParsedResults())->count(1);
});
