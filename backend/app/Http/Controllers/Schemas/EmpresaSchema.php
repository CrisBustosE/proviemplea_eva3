<?php

namespace App\Http\Controllers\Schemas;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "Empresa",
    title: "Empresa",
    description: "Modelo completo de empresa empleadora",
    required: ["nombre_empresa", "rut_empresa", "email", "tipo_empresa", "contacto_nombre", "contacto_email"]
)]
class EmpresaSchema
{
    #[OA\Property(type: "integer", example: 1)]
    public int $id;

    #[OA\Property(type: "string", example: "TechCorp SpA")]
    public string $nombre_empresa;

    #[OA\Property(type: "string", example: "76123456-7")]
    public string $rut_empresa;

    #[OA\Property(type: "string", format: "email", example: "rrhh@techcorp.cl")]
    public string $email;

    #[OA\Property(type: "string", format: "url", nullable: true)]
    public ?string $logo_url;

    #[OA\Property(type: "string", example: "Tecnología", nullable: true)]
    public ?string $rubro;

    #[OA\Property(type: "string", enum: ["contratacion-directa", "est", "outsourcing"])]
    public string $tipo_empresa;

    #[OA\Property(type: "string", nullable: true)]
    public ?string $presentacion;

    #[OA\Property(
        type: "array",
        items: new OA\Items(type: "string"),
        example: ["Seguro complementario", "Trabajo remoto"],
        nullable: true
    )]
    public ?array $beneficios;

    #[OA\Property(type: "string", example: "Ana López")]
    public string $contacto_nombre;

    #[OA\Property(type: "string", format: "email", example: "ana@techcorp.cl")]
    public string $contacto_email;

    #[OA\Property(type: "string", example: "+56912345678", nullable: true)]
    public ?string $contacto_telefono;

    #[OA\Property(type: "boolean", example: false)]
    public bool $validado;

    #[OA\Property(type: "boolean", example: true)]
    public bool $activo;

    #[OA\Property(type: "string", format: "date-time")]
    public string $created_at;

    #[OA\Property(type: "string", format: "date-time")]
    public string $updated_at;
}

#[OA\Schema(
    schema: "EmpresaInput",
    title: "Empresa Input",
    description: "Datos para crear o actualizar una empresa",
    required: ["nombre_empresa", "rut_empresa", "email", "tipo_empresa", "contacto_nombre", "contacto_email"]
)]
class EmpresaInputSchema
{
    #[OA\Property(type: "string", example: "TechCorp SpA")]
    public string $nombre_empresa;

    #[OA\Property(type: "string", example: "76123456-7")]
    public string $rut_empresa;

    #[OA\Property(type: "string", format: "email", example: "rrhh@techcorp.cl")]
    public string $email;

    #[OA\Property(type: "string", format: "url", nullable: true)]
    public ?string $logo_url;

    #[OA\Property(type: "string", example: "Tecnología", nullable: true)]
    public ?string $rubro;

    #[OA\Property(type: "string", enum: ["contratacion-directa", "est", "outsourcing"])]
    public string $tipo_empresa;

    #[OA\Property(type: "string", nullable: true)]
    public ?string $presentacion;

    #[OA\Property(
        type: "array",
        items: new OA\Items(type: "string"),
        nullable: true
    )]
    public ?array $beneficios;

    #[OA\Property(type: "string", example: "Ana López")]
    public string $contacto_nombre;

    #[OA\Property(type: "string", format: "email", example: "ana@techcorp.cl")]
    public string $contacto_email;

    #[OA\Property(type: "string", nullable: true)]
    public ?string $contacto_telefono;
}
