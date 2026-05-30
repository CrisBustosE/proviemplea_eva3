<?php

namespace App\Http\Controllers\Schemas;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "ContactoSolicitado",
    title: "Contacto Solicitado",
    description: "Solicitud de contacto entre empresa y talento"
)]
class ContactoSolicitadoSchema
{
    #[OA\Property(type: "integer", example: 1)]
    public int $id;

    #[OA\Property(type: "integer", example: 1)]
    public int $empresa_id;

    #[OA\Property(type: "integer", example: 1)]
    public int $persona_id;

    #[OA\Property(
        type: "string",
        enum: ["pendiente", "contactado", "entrevista", "seleccionado", "no-seleccionado", "proceso-cerrado"],
        example: "pendiente"
    )]
    public string $estado;

    #[OA\Property(type: "string", nullable: true)]
    public ?string $notas_admin;

    #[OA\Property(type: "string", format: "date-time", nullable: true)]
    public ?string $fecha_contacto;

    #[OA\Property(type: "string", format: "date-time", nullable: true)]
    public ?string $fecha_entrevista;

    #[OA\Property(type: "string", format: "date-time", nullable: true)]
    public ?string $fecha_resultado;

    #[OA\Property(type: "string", format: "date-time")]
    public string $created_at;

    #[OA\Property(type: "string", format: "date-time")]
    public string $updated_at;

    #[OA\Property(ref: "#/components/schemas/Empresa")]
    public mixed $empresa;

    #[OA\Property(ref: "#/components/schemas/Persona")]
    public mixed $persona;
}

#[OA\Schema(
    schema: "ContactoSolicitadoInput",
    title: "Contacto Solicitado Input",
    required: ["empresa_id", "persona_id"]
)]
class ContactoSolicitadoInputSchema
{
    #[OA\Property(type: "integer", example: 1)]
    public int $empresa_id;

    #[OA\Property(type: "integer", example: 1)]
    public int $persona_id;

    #[OA\Property(type: "string", nullable: true)]
    public ?string $notas_admin;
}
