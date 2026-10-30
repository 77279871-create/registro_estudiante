<?php
declare(strict_types=1);

class Estudiante
{
    private string $apellidos;
    private string $nombres;
    private string $fechaNacimiento;
    private string $genero;
    private string $celular;
    private string $carrera;
    private string $semestre;
    private string $hobby;

    public function __construct(
        string $apellidos,
        string $nombres,
        string $fechaNacimiento,
        string $genero,
        string $celular,
        string $carrera,
        string $semestre,
        string $hobby
    ) {
        $apellidos = trim($apellidos);
        $nombres = trim($nombres);
        $celular = trim($celular);
        $hobby = trim($hobby);

        if ($apellidos === '' || $nombres === '' || $fechaNacimiento === '' ||
            $genero === '' || $celular === '' || $carrera === '' ||
            $semestre === '' || $hobby === '') {
            throw new InvalidArgumentException('Todos los campos son obligatorios.');
        }

        if (!in_array($genero, ['Varón', 'Mujer'], true)) {
            throw new InvalidArgumentException('Selecciona un género válido.');
        }

        if (!preg_match('/^9[0-9]{8}$/', $celular)) {
            throw new InvalidArgumentException('El celular debe tener 9 dígitos y comenzar con 9.');
        }

        $fecha = DateTime::createFromFormat('!Y-m-d', $fechaNacimiento);
        $errores = DateTime::getLastErrors();
        $fechaInvalida = !$fecha ||
            ($errores && ($errores['warning_count'] > 0 || $errores['error_count'] > 0)) ||
            ($fecha && $fecha->format('Y-m-d') !== $fechaNacimiento);

        if ($fechaInvalida) {
            throw new InvalidArgumentException('La fecha de nacimiento no es válida.');
        }

        $nacimiento = new DateTimeImmutable($fechaNacimiento);
        $hoy = new DateTimeImmutable('today');

        if ($nacimiento > $hoy) {
            throw new InvalidArgumentException('La fecha no puede ser futura.');
        }

        $edad = $nacimiento->diff($hoy)->y;
        if ($edad < 17 || $edad > 65) {
            throw new InvalidArgumentException('La edad permitida es de 17 a 65 años.');
        }

        $this->apellidos = $apellidos;
        $this->nombres = $nombres;
        $this->fechaNacimiento = $fechaNacimiento;
        $this->genero = $genero;
        $this->celular = $celular;
        $this->carrera = $carrera;
        $this->semestre = $semestre;
        $this->hobby = $hobby;
    }

    public function getApellidos(): string { return $this->apellidos; }
    public function getNombres(): string { return $this->nombres; }
    public function getNombreCompleto(): string { return $this->apellidos . ', ' . $this->nombres; }
    public function getFechaNacimiento(): string { return $this->fechaNacimiento; }
    public function getGenero(): string { return $this->genero; }
    public function getCelular(): string { return $this->celular; }
    public function getCarrera(): string { return $this->carrera; }
    public function getSemestre(): string { return $this->semestre; }
    public function getHobby(): string { return $this->hobby; }

    public function obtenerEdad(): int
    {
        $nacimiento = new DateTimeImmutable($this->fechaNacimiento);
        $hoy = new DateTimeImmutable('today');
        return $nacimiento->diff($hoy)->y;
    }

    public function obtenerFechaFormateada(): string
    {
        return (new DateTimeImmutable($this->fechaNacimiento))->format('d/m/Y');
    }

    public function toArray(): array
    {
        return [
            'apellidos' => $this->apellidos,
            'nombres' => $this->nombres,
            'fechaNacimiento' => $this->fechaNacimiento,
            'genero' => $this->genero,
            'celular' => $this->celular,
            'carrera' => $this->carrera,
            'semestre' => $this->semestre,
            'hobby' => $this->hobby
        ];
    }
}