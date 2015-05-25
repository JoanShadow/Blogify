<?php namespace jorenvanhocht\Blogify\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use jorenvanhocht\Blogify\Models\Role;

class BlogifyAdminAuthenticate
{

	/**
	 * The Guard implementation.
	 *
	 * @var Guard
	 */
	protected $auth;

	/**
	 * Roles
	 *
	 * @var
	 */
	private $roles;

	/**
	 * Allowed roles id's
	 *
	 * @var array
	 */
	private $allowed_roles = [];

	/**
	 * Create a new filter instance.
	 *
	 * @param Guard $auth
	 * @param Role $role
	 */
	public function __construct(Guard $auth, Role $role)
	{
		$this->auth = $auth;
		$this->roles = $role->byAdminRoles()->get();
		$this->fillAllowedRolesArray();
	}

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		if ($this->auth->guest())
		{
			if ($request->ajax())
			{
				return response('Unauthorized.', 401);
			}
			else
			{
				return redirect()->route('admin.login');
			}
		}

		// Check if the user has permission to visit the admin panel
		if (! in_array($this->auth->user()->role_id, $this->allowed_roles))
		{
			return redirect()->route('admin.login');
		}

		return $next($request);
	}

	/**
	 * Loop through the allowed roles and push their
	 * id into the allowed_roles array
	 *
	 * @return void
	 */
	private function fillAllowedRolesArray()
	{
		foreach ($this->roles as $role)
		{
			array_push($this->allowed_roles, $role->id);
		}
	}

}