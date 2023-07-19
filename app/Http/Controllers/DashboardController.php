<?php

namespace App\Http\Controllers;

use App\Models\Depense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $mois = $request->mois;
        $annee = $request->annee;
        $recettes = DB::table('v_recette')
        ->select('*')
        ->where('mois', '=', $mois)
        ->where('annee', '=', $annee)->get();
        $sommeacte = DB::table('v_recette')->sum('montant_total');
        $sommebudget = DB::table('v_recette')->sum('budget');
        $totalrealisation = ($sommeacte*100)/$sommebudget; 
        
        $depenses = DB::table('v_depense')->get();
        $sommedepense = DB::table('v_depense')->sum('montant_total');
        $sommebudgetdepense = DB::table('v_depense')->sum('budget');
        $totalrealisationdepense = ($sommedepense*100)/$sommebudgetdepense;

        $beneficesomme = $sommeacte-$sommedepense;
        $beneficebudget = $sommebudget-$sommebudgetdepense;
        $benefice = ($totalrealisationdepense*100)/$totalrealisation;
        return view('admin.dashboard.index', compact(
            'recettes', 
            'sommeacte', 
            'sommebudget', 
            'totalrealisation',

            'depenses',
            'sommedepense',
            'sommebudgetdepense',
            'totalrealisationdepense',

            'beneficesomme',
            'beneficebudget',
            'benefice',
        ));
    }

    public function tableau(Request $request){
        
        $mois = $request->mois;
        $annee = $request->annee;

        // recette
        $recettes = DB::table('v_recette')
        ->select('*')
        ->where('mois', '=', $mois)
        ->where('annee', '=', $annee)->get();
        $sommeacte = DB::table('v_recette')
            ->where('mois', $mois)
            ->where('annee', $annee)
            ->sum('montant_total');        
        
        $sommebudget = DB::table('v_recette')
            ->where('mois', $mois)
            ->where('annee', $annee)
            ->sum('budget');
        $totalrealisation = ($sommeacte*100)/$sommebudget; 
        
        // depense
        $depenses = DB::table('v_depense')
        ->select('*')
        ->where('mois', '=', $mois)
        ->where('annee', '=', $annee)->get();
        $sommedepense = DB::table('v_depense')
            ->where('mois', $mois)
            ->where('annee', $annee)
            ->sum('montant_total'); 
        $sommebudgetdepense = DB::table('v_depense')
            ->where('mois', $mois)
            ->where('annee', $annee)
            ->sum('budget');         
        $totalrealisationdepense = ($sommedepense*100)/$sommebudgetdepense;

        // Benefice
        $beneficesomme = $sommeacte-$sommedepense;
        $beneficebudget = $sommebudget-$sommebudgetdepense;
        $benefice = ($totalrealisationdepense*100)/$totalrealisation;

        // if($beneficesomme < 0){
        //     $beneficesomme = 1;
        // }else{$beneficesomme;}

        if($benefice > 100)
        {
            $benefice = 100;
        }
        else{
            $benefice = $benefice;
        }
        if(($beneficesomme <0) || ($beneficebudget<=0)){
            $benefice = 0;
        }
        

        return view('admin.dashboard.index', compact(
            'recettes', 
            'sommeacte', 
            'sommebudget', 
            'totalrealisation',

            'depenses',
            'sommedepense',
            'sommebudgetdepense',
            'totalrealisationdepense',

            'beneficesomme',
            'beneficebudget',
            'benefice',
        ));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
