<?php

namespace App\Models;

use DB;
use App\Models\Images;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bar_Restaurant extends Model
{
    use SoftDeletes;

    protected $table = 'bar_restaurants';
    protected $softDelete = true;
    protected $primaryKey = 'restaurantId';
    protected $fillable = ['restaurantId'];

    function single_add($xx)
    {

        $obj = new Bar_Restaurant();

        if (isset($xx->place_id))
            $obj->yelpId = $xx->place_id;

        if (isset($xx->name))
            $obj->name = $xx->name;

        if (isset($xx->type))
            $obj->type = $xx->type;

        $obj->displayAddress = $xx->adr_address;

        foreach ($xx->address_components as $row)
        {

            if ($row->types[0] == "floor")
            {
                $obj->address .= $row->long_name . ', ';
            }

            if ($row->types[0] == "street_number")
            {
                $obj->address .= $row->long_name . ' ';
            }

            if ($row->types[0] == "route")
            {
                $obj->address .= $row->long_name;
            }

            if ($row->types[0] == "premise")
            {
                $obj->address .= $row->long_name . ', ';
            }

            if ($row->types[0] == "locality")
            {
                $obj->city .= $row->long_name;
            }

            if ($row->types[0] == "administrative_area_level_1")
            {
                $obj->state .= $row->long_name;
            }

            if ($row->types[0] == "postal_code")
            {
                $obj->zipCode .= $row->long_name;
            }
        }

        $obj->country = "United States";

        if (isset($xx->international_phone_number))
        {
            $string = preg_replace('/^\+?1|\|1|\D/', '', ($xx->international_phone_number));
            $obj->phone = $string;
        }

        if (isset($xx->review_count))
            $obj->reviewCount = $xx->review_count;

        if (isset($xx->rating))
            $obj->rating = $xx->rating;

        if (isset($xx->geometry->location->lat))
            $obj->latitude = $xx->geometry->location->lat;

        if (isset($xx->geometry->location->lng))
            $obj->longitude = $xx->geometry->location->lng;

        if (isset($xx->distance))
            $obj->distance = $xx->distance;

        $obj->save();

        if (isset($xx->photos))
        {
            foreach (@$xx->photos as $photo)
            {

                $userModel = new Images();
                $res = (object) array();
                $res->restaurantId = $obj->restaurantId;
                $res->new_image = @$photo->photo_reference;
                $res->type = 1;
                $response = $userModel->upload_images($res);
            }
        }
        if (isset($xx->image_url))
        {
            $userModel = new Images();
            $res = (object) array();
            $res->restaurantId = $obj->restaurantId;
            $res->new_image = $xx->image_url;
            $res->type = 1;
            $response = $userModel->upload_images($res);
        }
        return $obj;
    }
    //Admin Panel
    function search_bar_restaurant($request)
    {
        $limit = 10;
        $offset = 0;
        if (isset($request->limit) && !empty($request->limit))
            $limit = $request->limit;

        if (isset($request->offset) && !empty($request->offset))
            $offset = $request->offset;

        $query = Bar_Restaurant::query();

        $query->join('images', 'bar_restaurants.restaurantId', '=', 'images.restaurantId');
        $query->groupBy('images.restaurantId');
        if (isset($request->search_text) && !empty($request->search_text))
        {
            $query->where(function ($q) use ($request)
            {
                $q->Where('bar_restaurants.name', 'like', '%' . $request->search_text . '%')
                    ->orWhere('bar_restaurants.address', 'like', '%' . $request->search_text . '%')
                    ->orWhere('bar_restaurants.phone', 'like', '%' . $request->search_text . '%')
                    ->orWhere('bar_restaurants.city', 'like', '%' . $request->search_text . '%')
                    ->orWhere('bar_restaurants.state', 'like', '%' . $request->search_text . '%');
            });
        }

        $query->select(["bar_restaurants.*", "images.imageName as imageUrl", "images.type as imageType"]);


        if (isset($request->filter_status) && $request->filter_status != "")
            $query->where('bar_restaurants.status', '=', $request->filter_status);

        if (isset($request->filter_owner) && $request->filter_owner != "")
            $query->where('bar_restaurants.ownerId', '=', $request->filter_owner);

        if (isset($request->filterBy) && $request->filterBy == 2)
            $query->orderBy('bar_restaurants.updated_at', 'DESC');

        if (isset($request->order_by) && !empty($request->order_by))
        {
            if ($request->order_by == 'created_at-asc')
                $query->orderBy('bar_restaurants.created_at', 'ASC');
            elseif ($request->order_by == 'created_at-desc')
                $query->orderBy('bar_restaurants.created_at', 'DESC');
            elseif ($request->order_by == 'name-asc')
                $query->orderBy('name', 'ASC');
            elseif ($request->order_by == 'name-desc')
                $query->orderBy('name', 'DESC');
        }
        else
        {
            $query->orderBy('bar_restaurants.created_at', 'DESC');
        }

        $total_query = $query;
        $total_result = $total_query->get();
        $total = count($total_result);

        $query->limit($limit)->offset($offset);
        $data = $query->get();
        $length = count($data);
        if ($total <= $limit)
            $next = false;
        else
            $next = true;
        if ($length < $limit)
            $next = false;
        else
            $next = true;
        $total_offset_value = $offset + $limit;
        if ($total == $total_offset_value)
            $next = false;
        return ['list' => $data, 'total' => $total, 'next' => $next];


    }
    /// API
    function search_bar_restaurant_api($request)
    {
        $limit = 20;
        $offset = 0;
        if (isset($request->limit) && !empty($request->limit))
            $limit = $request->limit;

        if (isset($request->offset) && !empty($request->offset))
            $offset = $request->offset;

        $query = Bar_Restaurant::query();
        $query->join('images', 'bar_restaurants.restaurantId', '=', 'images.restaurantId');
        $query->groupBy('images.restaurantId');
        $query->where('bar_restaurants.status', '=', 1);
        if (isset($request->search_text) && !empty($request->search_text))
        {
            $query->where(function ($q) use ($request)
            {
                $q->Where('bar_restaurants.name', 'like', '%' . $request->search_text . '%')
                    ->orWhere('bar_restaurants.address', 'like', '%' . $request->search_text . '%')
                    ->orWhere('bar_restaurants.phone', 'like', '%' . $request->search_text . '%')
                    ->orWhere('bar_restaurants.city', 'like', '%' . $request->search_text . '%')
                    ->orWhere('bar_restaurants.state', 'like', '%' . $request->search_text . '%');
            });
        }

        if (isset($request->latitude) && isset($request->longitude))
        {
            $query->select(["bar_restaurants.*", "images.imageName as imageUrl", "images.type as imageType", DB::raw("ACOS( SIN( RADIANS( `latitude` ) ) * SIN( RADIANS($request->latitude) ) + COS( RADIANS( `latitude` ) )* COS( RADIANS($request->latitude)) * COS( RADIANS( `longitude` ) - RADIANS($request->longitude))) * 3959 AS distance")]);
        }
        if (isset($request->filterBy) && $request->filterBy == 3) //find favourite bar
        {
            $is_fav = Favourite::select('restaurantId')->where(['userId' => $request->userId, 'status' => 1])->get();
            $query->whereIn('bar_restaurants.restaurantId', $is_fav);
        }
        if (isset($request->filterBy) && $request->filterBy == 2)
        { //updated bar
            $query->orderBy('bar_restaurants.updated_at', 'DESC');
        }
        else
        {
            $query->orderBy("distance", 'ASC');
        }

        $total_query = $query;
        $total_result = $total_query->get();
        $total = count($total_result);

        $query->groupBy('bar_restaurants.restaurantId');
        $query->limit($limit)->offset($offset);
        $data = $query->get();
        $length = count($data);
        if ($total <= $limit)
            $next = false;
        else
            $next = true;
        if ($length < $limit)
            $next = false;
        else
            $next = true;
        $total_offset_value = $offset + $limit;
        if ($total == $total_offset_value)
            $next = false;
        return ['list' => $data, 'total' => $total, 'next' => $next];

    }


    function comment_count()
    {
        $a1 = Comment::select(['restaurantId', DB::raw('count(commentId) as comments_count')])
            ->groupBy('restaurantId')
            ->orderBy('comments_count', 'desc')
            ->get();
        foreach (@$a1 as $val)
        {
            $aaa['restaurantId'] = $val->restaurantId;
            @$array1[] = $aaa;
        }
        $a2 = Checkedin::select(['restaurantId', DB::raw('count(checkinId) as comments_count')])
            ->groupBy('restaurantId')
            ->orderBy('comments_count', 'desc')
            ->get();
        foreach (@$a2 as $val)
        {
            $bbb['restaurantId'] = $val->restaurantId;
            @$array2[] = $bbb;
        }
        $result = array_unique(array_merge(@$array1, @$array2), SORT_REGULAR);
        return @$result;
    }

    function Add_and_Update($request)
    {

        $restaurantId = 0;
        if (isset($request->restaurantId) && !empty($request->restaurantId))
            $restaurantId = $request->restaurantId;
        $obj = Bar_Restaurant::firstOrNew(['restaurantId' => $restaurantId]);

        if (isset($request->yelpId))
            $obj->yelpId = $request->yelpId;

        if (isset($request->ownerId))
            $obj->ownerId = $request->ownerId;

        if (isset($request->name))
            $obj->name = $request->name;

        if (isset($request->address))
            $obj->address = $request->address;

        if (isset($request->city))
            $obj->city = $request->city;

        if (isset($request->type))
            $obj->type = $request->type;

        if (isset($request->state))
            $obj->state = $request->state;

        if (isset($request->country))
            $obj->country = $request->country;

        if (isset($request->zipCode))
            $obj->zipCode = $request->zipCode;

        if (isset($request->phone))
            $obj->phone = $request->phone;

        if (isset($request->reviewCount))
            $obj->reviewCount = $request->reviewCount;

        if (isset($request->rating))
            $obj->rating = $request->rating;

        if (isset($request->latitude))
            $obj->latitude = $request->latitude;

        if (isset($request->longitude))
            $obj->longitude = $request->longitude;

        if (isset($request->distance))
            $obj->distance = $request->distance;

        if (isset($request->description))
            $obj->description = $request->description;

        $obj->save();

        if (isset($request->imageUrl) && !empty($request->imageUrl))
        {
            $res = (object) array();

            foreach ($request->files->all('imageUrl') as $key => $xx)
            {
                $imageHelper = new \ImageHelper();
                $new_image = $imageHelper->move_image_from_temp($xx);
                $userModel = new Images();
                $res->restaurantId = $obj->restaurantId;
                $res->new_image = $new_image;
                $res->type = 2;
                $response = $userModel->upload_images($res);
            }

            /* $userModel = new Images();
            $userModel->restaurantId = $obj->restaurantId;
            $userModel->type = 2;
            $userModel->save();
            */
        }
        return $obj;

    }
    function update_owner_status($request)
    {
        $obj = Bar_Restaurant::find($request->id);
        $obj->status = $request->status;
        $obj->save();
        return $obj;

    }


    function saveBarData($xx)
    {
        $obj = new Bar_Restaurant();

        $obj->address = "";
        $obj->city = "";
        $obj->state = "";
        $obj->country = "United States";
        $obj->zipCode = "";

        foreach ($xx->address_components as $row)
        {

            if ($row->types[0] == "floor")
            {
                $obj->address .= $row->long_name . ', ';
            }

            if ($row->types[0] == "street_number")
            {
                $obj->address .= $row->long_name . ' ';
            }

            if ($row->types[0] == "route")
            {
                $obj->address .= $row->long_name;
            }

            if ($row->types[0] == "premise")
            {
                $obj->address .= $row->long_name . ', ';
            }

            if ($row->types[0] == "locality")
            {
                $obj->city .= $row->long_name;
            }

            if ($row->types[0] == "administrative_area_level_1")
            {
                $obj->state .= $row->long_name;
            }

            if ($row->types[0] == "postal_code")
            {
                $obj->zipCode .= $row->long_name;
            }
        }

        if (isset($xx->place_id))
            $obj->yelpId = $xx->place_id;

        if (isset($xx->name))
            $obj->name = $xx->name;

        // if(isset($xx->type))
        $obj->type = 1;

        $obj->displayAddress = $xx->adr_address;

        if (isset($xx->international_phone_number))
        {
            $string = preg_replace('/^\+?1|\|1|\D/', '', ($xx->international_phone_number));
            $obj->phone = $string;
        }

        if (isset($xx->review_count))
            $obj->reviewCount = $xx->review_count;

        if (isset($xx->rating))
            $obj->rating = $xx->rating;

        if (isset($xx->geometry->location->lat))
            $obj->latitude = $xx->geometry->location->lat;

        if (isset($xx->geometry->location->lng))
            $obj->longitude = $xx->geometry->location->lng;

        if (isset($xx->distance))
            $obj->distance = $xx->distance;

        if (in_array('bar', $xx->types) && (strtolower($obj->city) == 'columbus' || strtolower($obj->city) == 'cincinnati'))
        {
            $obj->save();
        }
        else
        {
            return $obj;
        }


        if (isset($xx->photos))
        {
            foreach (@$xx->photos as $photo)
            {

                $userModel = new Images();
                $res = (object) array();
                $res->restaurantId = $obj->restaurantId;
                $res->new_image = @$photo->photo_reference;
                $response = $userModel->upload_images($res);
            }
        }
        if (isset($xx->image_url))
        {
            $userModel = new Images();
            $res = (object) array();
            $res->restaurantId = $obj->restaurantId;
            $res->new_image = $xx->image_url;
            $response = $userModel->upload_images($res);
        }
        return $obj;
    }

}
