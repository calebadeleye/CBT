<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'question',
        'options',
        'answer',
        'mark',
        'subject',
        'image',
        'subject_topic'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'options' => 'array',
    ];

    /**
     * Subject array.
     *
     * @var array
     */
    public $subjects = [
        'ENG' => 'Use Of English',
        'MTH' => 'Mathematics',
        'BIO' => 'Biology',
        'PHY' => 'Physics',
        'CHEM' => 'Chemistry',
        'GEO' => 'Geography',
        'HIS' => 'History',
        'AGRIC' => 'Agric Science ',
        'ECO' => 'Economics ',
        'CRK' => 'Christian Religious Knowledge ',
        'LIT' => 'Literature-in-English ',
        'GOV' => 'Government ',
        'FRENCH' => 'French ',
        'ARABIC' => 'Arabic ',
        'HAUSA' => 'Hausa ',
        'IGBO' => 'Igbo ',
        'YORUBA' => 'Yoruba ',
        'MUSIC' => 'Music ',
        'YORUBA' => 'Yoruba ',
        'COMMERCE' => 'Commerce ',
    ];


    /**
     * Subject_Topic array.
     *
     * @var array
     */
    public $subjects_topic = [
        'Comprehension' => 3,
        'Close Passage' => 2,
        'Reading Text' => 1,
        'Sentence Interpretation' => 2,
        'Antonyms' => 2,
        'Synonyms' => 1,
        'Basic Grammar' => 2,
        'Vowels' => 1,
        'Consonants' => 1,
        'Rhymes' => 1,
        'Word Stress' => 1,
        'Emphatic Stress' => 1,
        'Non' => 2.5
    ];

    public static function getAll()
    {
        return self::orderBy('created_at','desc')->get();
        
    }

}
