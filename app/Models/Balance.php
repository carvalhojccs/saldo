<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\User;

class Balance extends Model
{
	//desativa o timestamps na migration
	public $timestamps = false;
        
        public function deposit(float $value) : Array 
        {
            DB::beginTransaction();
            
            //verifica se já existe um saldo. Se tiver, retorna o saldo, caso contrario retorna 0
            $totalBefore = $this->amount ? $this->amount : 0;
            
            $this->amount += number_format($value,2,',','.');
            $deposit = $this->save();
            
            //pega o usuário logado e insere os dados no historic
            $historic = auth()->user()->historics()->create([
                'type'          => 'I',
                'amount'        => $value,               
                'total_before'  => $totalBefore,     
                'total_after'   => $this->amount,
                'date'          => date('Ymd')
            ]);
            
            if($deposit && $historic)
            {
                DB::commit();
                
                return [
                    'success' => true,
                    'message' => 'Sucesso ao carregar'
                ];
            }
            else
            {
                DB::rollback();
              
                return [
                    'success' => false,
                    'message' => 'Falha ao carregar'
            ];
            }
        }
        
        public function withdraw(float $value) : Array 
        {
            //verifica se te saldo suficiente
            if($this->amount < $value)
                return [
                  'success' => 'false',
                  'message' => 'Saldo insuficiente',
                ];
            
            DB::beginTransaction();
            
            //verifica se já existe um saldo. Se tiver, retorna o saldo, caso contrario retorna 0
            $totalBefore = $this->amount ? $this->amount : 0;
            
            $this->amount -= number_format($value,2,',','.');
            $deposit = $this->save();
            
            //pega o usuário logado e insere os dados no historic
            $historic = auth()->user()->historics()->create([
                'type'          => 'O',
                'amount'        => $value,               
                'total_before'  => $totalBefore,     
                'total_after'   => $this->amount,
                'date'          => date('Ymd')
            ]);
            
            if($deposit && $historic)
            {
                DB::commit();
                
                return [
                    'success' => true,
                    'message' => 'Sucesso ao retirar'
                ];
            }
            else
            {
                DB::rollback();
              
                return [
                    'success' => false,
                    'message' => 'Falha ao retirar'
            ];
            }
        }
        
        public function transfer(float $value, User $sender): Array 
        {
             //verifica se tem saldo suficiente
            if($this->amount < $value)
                return [
                  'success' => 'false',
                  'message' => 'Saldo insuficiente',
                ];
            
            DB::beginTransaction();
            
            //atualiza o próprio saldo
            $totalBefore = $this->amount ? $this->amount : 0;
            $this->amount -= number_format($value,2,',','.');
            $transfer = $this->save();
            
            //pega o usuário logado e insere os dados no historic
            $historic = auth()->user()->historics()->create([
                'type'                  => 'T',
                'amount'                => $value,               
                'total_before'          => $totalBefore,     
                'total_after'           => $this->amount,
                'date'                  => date('Ymd'),
                'user_id_transaction'   => $sender->id
            ]);
            
            //atualiza o saldo do recebedor
            $senderBalance = $sender->balance()->firstOrCreate([]);
            
            $totalBeforeSender = $senderBalance->amount ? $this->senderBalance : 0;
            $senderBalance->amount += number_format($value,2,',','.');
            $transferSender = $senderBalance->save();
            
            //pega o usuário logado e insere os dados no historic
            $historicSender = $sender->historics()->create([
                'type'                  => 'I',
                'amount'                => $value,               
                'total_before'          => $totalBeforeSender,     
                'total_after'           => $senderBalance->amount,
                'date'                  => date('Ymd'),
                'user_id_transaction'   => auth()->user()->id
            ]);
            
            
            if($transfer && $historic && $transferSender && $historicSender)
            {
                DB::commit();
                
                return [
                    'success' => true,
                    'message' => 'Sucesso ao transferir'
                ];
            }
            
            DB::rollback();
              
                return [
                    'success' => false,
                    'message' => 'Falha ao transferir'
            ];
        }

}
