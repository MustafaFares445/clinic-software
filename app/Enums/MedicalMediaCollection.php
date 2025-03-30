<?php

namespace App\Enums;

enum MedicalMediaCollection: string
{
    case XRay = 'x-ray';
    case Tests = 'tests';
    case MriScans = 'mri-scans';//
    case CtScans = 'ct-scans';//
    case Ultrasound = 'ultrasound';
    case LabReports = 'lab-reports';//
    case MedicalReports = 'medical-reports';//
    case PatientHistory = 'patient-history';
    case Prescriptions = 'prescriptions';
    case EcgRecords = 'ecg-records';

    public static function values(): array
    {
        return array_values(self::cases());
    }
}