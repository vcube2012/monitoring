<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOwnerWalletRequest;
use App\Http\Requests\UpdateOwnerWalletRequest;
use App\Models\OwnerWallet;
use App\Models\Project;
use Attestto\SolanaPhpSdk\Keypair;
use Illuminate\Http\Request;

class OwnerWalletController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(string $project_token, int $user_id, Request $request)
    {

        $project = Project::query()->where('token', $project_token)->firstOrFail();
        $wallet = Keypair::fromSecretKey( Keypair::generate()->getSecretKey());

        $data = $project->wallets()->firstOrCreate([
            'user_id' => $user_id,
        ], [
            'wallet' => $wallet->publicKey->toBase58(),
            'secret_key' => $wallet->getSecretKey()->toBase58String()
        ]);


        return ['wallet' => $data->wallet];
    }

}
