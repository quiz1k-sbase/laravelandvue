<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class UserExport implements FromCollection, WithHeadings, ShouldAutoSize, WithMapping
{
    use Exportable;
    /**
    * @return \Illuminate\Support\Collection
    */
    public function headings(): array
    {
        return [
            'Id',
            'Username',
            'Email',
            'First name',
            'Last name',
            'Phone',
            'Created at',
            'Balance',
            'Posts',
        ];
    }

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function collection()
    {
        return $this->user;
    }

    public function map($user): array
    {
        $rows = [];
        $str = '';
        $pattern = '/$/im';
        $replace = ', ';
        foreach($user->posts as $post)
        {
            //array_push($rows, $post->text_en);
            $str .= preg_replace($pattern, $replace, $post->text_en);
        }

        $str = rtrim($str, ', ');

        return [
            $user->id,
            $user->username,
            $user->email,
            $user->first_name,
            $user->last_name,
            $user->phone,
            $user->created_at,
            $user->balance,
            $str,
        ];
    }
}
