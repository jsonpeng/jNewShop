<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateShopTimesRequest;
use App\Http\Requests\UpdateShopTimesRequest;
use App\Repositories\ShopTimesRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class ShopTimesController extends AppBaseController
{
    /** @var  ShopTimesRepository */
    private $shopTimesRepository;

    public function __construct(ShopTimesRepository $shopTimesRepo)
    {
        $this->shopTimesRepository = $shopTimesRepo;
    }

    /**
     * Display a listing of the ShopTimes.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->shopTimesRepository->pushCriteria(new RequestCriteria($request));
        $shopTimes = $this->shopTimesRepository->all();

        return view('shop_times.index')
            ->with('shopTimes', $shopTimes);
    }

    /**
     * Show the form for creating a new ShopTimes.
     *
     * @return Response
     */
    public function create()
    {
        return view('shop_times.create');
    }

    /**
     * Store a newly created ShopTimes in storage.
     *
     * @param CreateShopTimesRequest $request
     *
     * @return Response
     */
    public function store(CreateShopTimesRequest $request)
    {
        $input = $request->all();

        $shopTimes = $this->shopTimesRepository->create($input);

        Flash::success('Shop Times saved successfully.');

        return redirect(route('shopTimes.index'));
    }

    /**
     * Display the specified ShopTimes.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $shopTimes = $this->shopTimesRepository->findWithoutFail($id);

        if (empty($shopTimes)) {
            Flash::error('Shop Times not found');

            return redirect(route('shopTimes.index'));
        }

        return view('shop_times.show')->with('shopTimes', $shopTimes);
    }

    /**
     * Show the form for editing the specified ShopTimes.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $shopTimes = $this->shopTimesRepository->findWithoutFail($id);

        if (empty($shopTimes)) {
            Flash::error('Shop Times not found');

            return redirect(route('shopTimes.index'));
        }

        return view('shop_times.edit')->with('shopTimes', $shopTimes);
    }

    /**
     * Update the specified ShopTimes in storage.
     *
     * @param  int              $id
     * @param UpdateShopTimesRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateShopTimesRequest $request)
    {
        $shopTimes = $this->shopTimesRepository->findWithoutFail($id);

        if (empty($shopTimes)) {
            Flash::error('Shop Times not found');

            return redirect(route('shopTimes.index'));
        }

        $shopTimes = $this->shopTimesRepository->update($request->all(), $id);

        Flash::success('Shop Times updated successfully.');

        return redirect(route('shopTimes.index'));
    }

    /**
     * Remove the specified ShopTimes from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $shopTimes = $this->shopTimesRepository->findWithoutFail($id);

        if (empty($shopTimes)) {
            Flash::error('Shop Times not found');

            return redirect(route('shopTimes.index'));
        }

        $this->shopTimesRepository->delete($id);

        Flash::success('Shop Times deleted successfully.');

        return redirect(route('shopTimes.index'));
    }
}
