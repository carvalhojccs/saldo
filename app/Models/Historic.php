<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\User;

class Historic extends Model
{
    protected $fillable = [
        'type',
        'amount',
        'total_before',
        'total_after',
        'user_id_transaction',
        'date'
    ];
    
    //usando scopo local para filtrar usuário logado
    public function scopeUserAuth($query) 
    {
        return $query->where('user_id', auth()->user()->id);
    }
    
    public function user() 
    {
        return $this->belongsTo(User::class);
    }

    public function userSender() 
    {
        return $this->belongsTo(User::class,'user_id_transaction');
    }
    
    public function type($type = null)
    {
        $types = [
            'I' => 'Entrada',
            'O' => 'Seque',
            'T' => 'Transferencia',
        ];
        
        if(!$type)
            return $types;
        
        if($this->user_id_transaction != null && $type == 'I')
                return 'Recebido';
        
        return $types[$type];
    }
    
    public function getDateAttribute($value) 
    {
        return Carbon::parse($value)->format('d/m/Y');
    }
    
    public function search(Array $data, $totalPage)
    {
        return $this->where(function($query) use ($data){
           if (isset($data['id']))
               $query->where('id',$data['id']);
           
           if (isset($data['date']))
               $query->where('date',$data['date']);
           
           if (isset($data['type']))
               $query->where('type',$data['type']);
           
            
        })
        //filtra apenas os resultados para o usuário logado no sistema
        //->where('user_id', auth()->user()->id)
        //utilização do escopo local
        ->userAuth()
        ->with(['userSender'])
        ->paginate($totalPage);
    }
}
