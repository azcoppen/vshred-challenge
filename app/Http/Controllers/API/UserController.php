<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

use Spatie\QueryBuilder\QueryBuilder;

use App\Models\User;

use App\Http\Resources\Users AS UserCollection;
use App\Http\Resources\User as UserResource;

use App\Http\Requests\StoreUser;
use App\Http\Requests\UpdateUser;

use App\Notifications\Welcome;

use \Exception;

class UserController extends Controller
{
    /**************************************************************************
    NOTE: This ISN'T a true repository pattern.

    For a real one: https://github.com/andersao/l5-repository
    *************************************************************************/

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index (Request $request) //: UserCollection --> we can't do this, can we? Laravel's resources aren't available as exceptions and PHP has no mixed return types!
    {
        $this->authorize (__FUNCTION__, User::class);

        try
        {
            if ( $request->user()->hasRole ('admin') )
            {
                $users = QueryBuilder::for(User::class)
                    ->allowedIncludes ('images')
                    ->allowedFilters('email')
                    ->get();
            }
            else
            {
                $users = collect ( $request->user () );
            }

            return new UserCollection ($users);
        }
        catch (Exception $e)
        {
            return response ()->json ([
                'error' => $e->getMessage ()
            ], 422);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store (StoreUser $request)  //: UserResource --> we can't do this, can we? Laravel's resources aren't available as exceptions and PHP has no mixed return types!
    {
        $this->authorize (__FUNCTION__, User::class);

        try
        {

            $user = User::create ($request->except (['_token']));

            $user->notify ( new Welcome );

            return new UserResource ($user);

        }
        catch (Exception $e)
        {
            return response ()->json ([
                'error' => $e->getMessage ()
            ], 422);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show (Request $request, User $user) //: UserResource --> we can't do this, can we? Laravel's resources aren't available as exceptions and PHP has no mixed return types!
    {
        $this->authorize (__FUNCTION__, $user);

        try
        {
            if ( $request->has ('include') && $request->get('include') == 'images' ) // This is a horrible hack
            {
                $user->load (['images']);
            }

            return new UserResource ($user);
        }
        catch (Exception $e)
        {
            return response ()->json ([
                'error' => $e->getMessage ()
            ], 422);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update (UpdateUser $request, User $user)  //: UserResource --> we can't do this, can we? Laravel's resources aren't available as exceptions and PHP has no mixed return types!
    {
        $this->authorize (__FUNCTION__, $user);

        try
        {
            $user->update ($request->except (['_token']));

            return new UserResource ($user);
        }
        catch (Exception $e)
        {
            return response ()->json ([
                'error' => $e->getMessage ()
            ], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy (Request $request, User $user) : JsonResponse
    {
        $this->authorize (__FUNCTION__, $user);

        try
        {

            $user->delete();

            return response ()->json ([
                'deleted' => true
            ], 200);

        }
        catch (Exception $e)
        {
            return response ()->json ([
                'error' => $e->getMessage ()
            ], 422);
        }
    }
}
