<?php 

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CouponController extends Controller
{
    /**
     * Afficher la liste des coupons
     */
    public function index()
    {
        $coupons = Coupon::latest()->paginate(10);
        return view('admin.coupons.index', compact('coupons'));
    }

    /**
     * Afficher le formulaire de création de coupon
     */
    public function create()
    {
        return view('admin.coupons.create');
    }

    /**
     * Enregistrer un nouveau coupon
     */
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:coupons,code',
            'description' => 'nullable',
            'type' => 'required|in:fixed,percentage',
            'value' => 'required|numeric|min:0',
            'max_uses' => 'nullable|integer|min:1',
            'min_order_amount' => 'nullable|numeric|min:0',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after_or_equal:starts_at',
            'is_active' => 'boolean',
        ]);

        // Valider le pourcentage maximum à 100%
        if ($request->type === 'percentage' && $request->value > 100) {
            return back()->withErrors(['value' => 'La valeur en pourcentage ne peut pas dépasser 100%.']);
        }

        // Créer le coupon
        Coupon::create([
            'code' => strtoupper($request->code),
            'description' => $request->description,
            'type' => $request->type,
            'value' => $request->value,
            'max_uses' => $request->max_uses,
            'min_order_amount' => $request->min_order_amount,
            'starts_at' => $request->starts_at,
            'expires_at' => $request->expires_at,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.coupons.index')
            ->with('success', 'Coupon créé avec succès.');
    }

    /**
     * Afficher le formulaire de modification d'un coupon
     */
    public function edit(Coupon $coupon)
    {
        return view('admin.coupons.edit', compact('coupon'));
    }

    /**
     * Mettre à jour un coupon existant
     */
    public function update(Request $request, Coupon $coupon)
    {
        $request->validate([
            'code' => 'required|unique:coupons,code,' . $coupon->id,
            'description' => 'nullable',
            'type' => 'required|in:fixed,percentage',
            'value' => 'required|numeric|min:0',
            'max_uses' => 'nullable|integer|min:' . $coupon->used_times,
            'min_order_amount' => 'nullable|numeric|min:0',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after_or_equal:starts_at',
            'is_active' => 'boolean',
        ]);

        // Valider le pourcentage maximum à 100%
        if ($request->type === 'percentage' && $request->value > 100) {
            return back()->withErrors(['value' => 'La valeur en pourcentage ne peut pas dépasser 100%.']);
        }

        // Mettre à jour le coupon
        $coupon->update([
            'code' => strtoupper($request->code),
            'description' => $request->description,
            'type' => $request->type,
            'value' => $request->value,
            'max_uses' => $request->max_uses,
            'min_order_amount' => $request->min_order_amount,
            'starts_at' => $request->starts_at,
            'expires_at' => $request->expires_at,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.coupons.index')
            ->with('success', 'Coupon mis à jour avec succès.');
    }

    /**
     * Supprimer un coupon
     */
    public function destroy(Coupon $coupon)
    {
        $coupon->delete();
        return redirect()->route('admin.coupons.index')
            ->with('success', 'Coupon supprimé avec succès.');
    }

    /**
     * Générer un code de coupon aléatoire
     */
    public function generateCode()
    {
        $code = strtoupper(Str::random(8));
        
        // S'assurer que le code est unique
        while (Coupon::where('code', $code)->exists()) {
            $code = strtoupper(Str::random(8));
        }
        
        return response()->json(['code' => $code]);
    }
}