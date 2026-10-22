<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Services\Ecommerce;

use JsonException;
use MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\CommandEnvelope;
use MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\JaneObjectNormalizer;
use MyParcelNL\Sdk\Exception\InvalidCommandException;
use MyParcelNL\Sdk\Exception\UnknownCommandException;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Serializer;
use TypeError;

/**
 * Reads the body of a command that MyParcel sends to your commandEndpoint into its generated model.
 *
 * Check the request signature and the connection id first: this reads the body, it does not
 * authenticate it.
 *
 *     try {
 *         $command = (new CommandReader())->read($rawBody);
 *     } catch (UnknownCommandException $exception) {
 *         // reply 404
 *     } catch (InvalidCommandException $exception) {
 *         // reply 400
 *     }
 *
 *     if ($command instanceof OrderShipmentsCommand) {
 *         // reply 202, then handle it
 *     }
 */
final class CommandReader
{
    /**
     * @var \Symfony\Component\Serializer\Serializer
     */
    private $serializer;

    public function __construct()
    {
        $this->serializer = new Serializer([new ArrayDenormalizer(), new JaneObjectNormalizer()]);
    }

    /**
     * Read a command body into the model for its type, such as OrderShipmentsCommand.
     *
     * An enum value the SDK does not know stays the raw string, so a new value from MyParcel does
     * not stop the read.
     *
     * @param  string $body The raw request body, exactly as received.
     * @return \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\CommandEnvelope The model for the
     *         command type, a subclass such as OrderShipmentsCommand. Never a plain CommandEnvelope.
     * @throws \MyParcelNL\Sdk\Exception\UnknownCommandException For a type this SDK version does not know.
     * @throws \MyParcelNL\Sdk\Exception\InvalidCommandException For a body that is not a command.
     */
    public function read(string $body): CommandEnvelope
    {
        try {
            $data = json_decode($body, true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $exception) {
            throw new InvalidCommandException('The command body is not JSON: ' . $exception->getMessage(), 0, $exception);
        }

        if (!is_array($data) || array_is_list($data)) {
            throw new InvalidCommandException('The command body is not a JSON object');
        }

        // The type picks the model, so it is checked here and not left to the generated code.
        if (!isset($data['type']) || !is_string($data['type'])) {
            throw new InvalidCommandException('The command has no type');
        }

        // @todo janephp#1126: once the generated code throws for a missing required field, wrap that
        //  exception in InvalidCommandException here.
        try {
            $command = $this->serializer->denormalize($data, CommandEnvelope::class);
        } catch (TypeError $error) {
            throw new InvalidCommandException('A command field has the wrong type: ' . $error->getMessage(), 0, $error);
        }

        // The generated normalizer picks the model from the spec's discriminator mapping, and falls
        // back to the envelope for a type the mapping does not name.
        if (CommandEnvelope::class === get_class($command)) {
            throw new UnknownCommandException($command->type);
        }

        return $command;
    }
}
