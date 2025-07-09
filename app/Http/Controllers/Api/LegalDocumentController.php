<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LegalDocument;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class LegalDocumentController extends Controller
{
    use ResponseTrait;

    public function __construct()
    {
        $this->middleware('auth:api')->only([
            'updatePrivacyPolicy',
            'updateTermsOfService',
        ]);
        $this->middleware('permission:update_privacy_policy')->only('updatePrivacyPolicy');
        $this->middleware('permission:update_terms_of_service')->only('updateTermsOfService');
    }

    /**
     * Get the latest published privacy policy.
     */
    public function getPrivacyPolicy()
    {
        $doc = LegalDocument::where('type', 'privacy_policy')
            ->where('status', 'published')
            ->orderByDesc('version')
            ->first();
        if (!$doc) {
            return $this->error('Privacy Policy not found', 404);
        }
        return $this->success([
            'content' => $doc->content,
            'version' => $doc->version,
        ], 'Privacy Policy retrieved successfully');
    }

    /**
     * Update (create new version) of privacy policy.
     */
    public function updatePrivacyPolicy(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'content' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors()->first(), 422);
        }


        return DB::transaction(function () use ($request) {
            $latest = LegalDocument::where('type', 'privacy_policy')->orderByDesc('version')->first();
            $newVersion = $latest ? $latest->version + 1 : 1;
            LegalDocument::where('type', 'privacy_policy')->update(['status' => 'draft']);
            $doc = LegalDocument::create([
                'type' => 'privacy_policy',
                'content' => $request->content,
                'version' => $newVersion,
                'status' => 'published',
            ]);
            return $this->success([
                'content' => $doc->content,
                'version' => $doc->version,
            ], 'Privacy Policy updated successfully');
        });
    }

    /**
     * Get the latest published terms of service.
     */
    public function getTermsOfService()
    {
        $doc = LegalDocument::where('type', 'terms_of_service')
            ->where('status', 'published')
            ->orderByDesc('version')
            ->first();
        if (!$doc) {
            return $this->error('Terms of Service not found', 404);
        }
        return $this->success([
            'content' => $doc->content,
            'version' => $doc->version,
        ], 'Terms of Service retrieved successfully');
    }

    /**
     * Update (create new version) of terms of service.
     */
    public function updateTermsOfService(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'content' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors()->first(), 422);
        }
        
        return DB::transaction(function () use ($request) {
            $latest = LegalDocument::where('type', 'terms_of_service')->orderByDesc('version')->first();
            $newVersion = $latest ? $latest->version + 1 : 1;
            LegalDocument::where('type', 'terms_of_service')->update(['status' => 'draft']);
            $doc = LegalDocument::create([
                'type' => 'terms_of_service',
                'content' => $request->content,
                'version' => $newVersion,
                'status' => 'published',
            ]);
            return $this->success([
                'content' => $doc->content,
                'version' => $doc->version,
            ], 'Terms of Service updated successfully');
        });
    }
} 