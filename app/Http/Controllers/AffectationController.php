<?php
namespace App\Http\Controllers;

use App\Models\Affectation;
use App\Models\Agent;
use App\Models\Courrier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class AffectationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $affectations = Affectation::with(['courrier', 'agent'])->get();
        // dd($affectations);die;
        return view('affectations.index', compact('affectations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $courriers = Courrier::all();
        $agents = Agent::all();
        // dd($agents);
        return view('affectations.create', compact('courriers', 'agents'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'courrier_id' => 'required|exists:courriers,id',
            'agent_id' => 'required|exists:agents,id',
            'statut' => 'required|string|max:255',
            'commentaires' => 'nullable|string',
            // date_affectation will use default
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $datas = $request->all();
        $datas['date_affectation'] = date('Y-m-d H:i:s');
        $datas['date_traitement'] = date('Y-m-d H:i:s');
        Affectation::create($datas);

        return redirect()->route('affectations.index')->with('success', 'Affectation created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Affectation $affectation)
    {
        $affectation->load(['courrier', 'agent']);
        return view('affectations.show', compact('affectation'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Affectation $affectation)
    {
        $courriers = Courrier::all();
        $agents = Agent::all();
        return view('affectations.edit', compact('affectation', 'courriers', 'agents'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Affectation $affectation)
    {
        $validator = Validator::make($request->all(), [
            'courrier_id' => 'required|exists:courriers,id',
            'agent_id' => 'required|exists:agents,id',
            'statut' => 'required|string|max:255',
            'commentaires' => 'nullable|string',
            'date_traitement' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $affectation->update($request->all());

        return redirect()->route('affectations.index')->with('success', 'Affectation updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Affectation $affectation)
    {
        $affectation->delete();

        return redirect()->route('affectations.index')->with('success', 'Affectation deleted successfully.');
    }

    /**
     * Custom method to update only the status.
     */
    public function updateStatus(Request $request, Affectation $affectation)
    {
        $request->validate([
            'statut' => 'required|string|max:255',
        ]);

        $affectation->statut = $request->statut;
        if ($request->statut == 'completed' && !$affectation->date_traitement) {
            $affectation->date_traitement = Carbon::now();
        }
        $affectation->save();

        return redirect()->back()->with('success', 'Affectation status updated.');
    }
}


