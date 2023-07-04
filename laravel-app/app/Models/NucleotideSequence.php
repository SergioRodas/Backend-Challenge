<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NucleotideSequence extends Model
{
    use HasFactory;
    protected $table = 'nucleotide_sequences';

    protected $fillable = [
        'sequence',
        'has_mutation',
    ];
}
