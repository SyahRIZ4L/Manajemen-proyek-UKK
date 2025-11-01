<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectAdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu');
        }

        $user = Auth::user();

        // Check if user has project admin role
        if (!$this->hasProjectAdminRole($user)) {
            abort(403, 'Akses ditolak. Anda tidak memiliki permission sebagai Project Admin.');
        }

        return $next($request);
    }

    /**
     * Check if user has project admin role
     */
    private function hasProjectAdminRole($user)
    {
        // Mock implementation - replace with actual role checking
        // This would typically check against a roles table or user role field
        return in_array($user->email, $this->getProjectAdminEmails())
               || $user->role === 'project_admin'
               || $user->role === 'admin';
    }

    /**
     * Get list of project admin emails (temporary implementation)
     */
    private function getProjectAdminEmails()
    {
        return [
            'admin@example.com',
            'project.admin@example.com',
            'manager@example.com'
        ];
    }
}
