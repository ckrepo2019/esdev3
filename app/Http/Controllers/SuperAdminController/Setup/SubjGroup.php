<?php

namespace App\Http\Controllers\SuperAdminController\Setup;

use Illuminate\Http\Request;
use File;
use DB;
use Image;

class SubjGroup extends \App\Http\Controllers\Controller
{
    public static function subjgroup(Request $request){

    
        $subjgroup = DB::table('setup_subjgroups')
                          ->where('deleted',0)
                          ->select(
                                'sort',
                                'id',
                                'sortnum',
                                'description',
                                'description as text'
                          )
                          ->get();

        return $subjgroup;

    }


    // public static function subjgroup_datatable(Request $request){

    //     $search = $request->get('search');
    //     $search = $search['value'];

    //     $subjgroup = DB::table('setup_subjgroups')
    //                         ->where(function($query) use($search){
    //                             $query->orWhere('sortnum','like','%'.$search.'%');
    //                             $query->orWhere('description','like','%'.$search.'%');
    //                         })
    //                         ->take($request->get('length'))
    //                         ->skip($request->get('start'))
    //                         ->where('deleted',0)
    //                         ->select(
    //                                 'sort',
    //                                 'id',
    //                                 'sortnum',
    //                                 'description',
    //                                 'description as text'
    //                         )
    //                       ->get();

    //     $subjgroup_count = DB::table('setup_subjgroups')
    //                       ->where(function($query) use($search){
    //                           $query->orWhere('sortnum','like','%'.$search.'%');
    //                           $query->orWhere('description','like','%'.$search.'%');
    //                       })
    //                     ->count();


    //     return @json_encode((object)[
    //         'data'=>$subjgroup,
    //         'recordsTotal'=>$subjgroup_count,
    //         'recordsFiltered'=>$subjgroup_count
    //     ]);
          

    // }

     // Default subject groups
        // $defaultGroups = [
        //     ['sortnum' => 1, 'description' => 'General Courses'],
        //     ['sortnum' => 2, 'description' => 'Professional Courses'],
        //     ['sortnum' => 3, 'description' => 'Major / Specialization Courses'],
        //     ['sortnum' => 4, 'description' => 'Electives'],
        //     ['sortnum' => 5, 'description' => 'Mandated Courses (PE & NSTP)']
        // ];

       
        // foreach ($defaultGroups as $group) {
        //     DB::table('setup_subjgroups')->updateOrInsert(
        //         ['description' => $group['description']],
        //         ['sortnum' => $group['sortnum'], 'deleted' => 0]
        //     );
        // }

    public static function subjgroup_datatable(Request $request)
    {
        $defaultGroups = [
            ['sortnum' => 1, 'description' => 'General Courses'],
            ['sortnum' => 2, 'description' => 'Professional Courses'],
            ['sortnum' => 3, 'description' => 'Major / Specialization Courses'],
            ['sortnum' => 4, 'description' => 'Electives'],
            ['sortnum' => 5, 'description' => 'Mandated Courses (PE & NSTP)']
        ];

        // Check if setup_subjgroups table is empty
        $count = DB::table('setup_subjgroups')->count();

        if ($count == 0) {
            // Insert default groups
            foreach ($defaultGroups as $group) {
                DB::table('setup_subjgroups')->insert([
                    'sortnum' => $group['sortnum'],
                    'description' => $group['description'],
                    'deleted' => 0
                ]);
            }
        }

        $search = $request->get('search')['value'] ?? '';
    
        $subjgroup = DB::table('setup_subjgroups')
            ->leftJoin('college_prospectus', function($join) {
                $join->on('college_prospectus.subjgroup', '=', 'setup_subjgroups.id')
                     ->where('college_prospectus.deleted', 0);
            })
            ->where('setup_subjgroups.deleted', 0)
            ->where(function($query) use ($search) {
                $query->orWhere('setup_subjgroups.sortnum', 'like', '%' . $search . '%')
                      ->orWhere('setup_subjgroups.description', 'like', '%' . $search . '%');
            })
            ->groupBy(
                'setup_subjgroups.sort',
                'setup_subjgroups.id',
                'setup_subjgroups.sortnum',
                'setup_subjgroups.description'
            )
            ->select(
                'setup_subjgroups.sort',
                'setup_subjgroups.id',
                'setup_subjgroups.sortnum',
                'setup_subjgroups.description',
                DB::raw('COALESCE(SUM(college_prospectus.credunits), 0) as totalcredunits'),
                DB::raw('COUNT(college_prospectus.id) as totalprospectus')
            )
            ->orderBy('setup_subjgroups.sortnum')
            ->get();
    
        $subjgroup_count = $subjgroup->count();
        $filtered_count = $subjgroup->count();
    
        return response()->json([
            'data' => $subjgroup,
            'recordsTotal' => $subjgroup_count,
            'recordsFiltered' => $filtered_count
        ]);
    }

    // public static function displaySubjperSubjgroup_datatable(Request $request)
    // {
      
    //     $subjgroupId = $request->get('subjgroup_id');

    //     $search = $request->get('search')['value'] ?? '';
    //     // Base query to fetch data from college_prospectus
    //     $subjprospectus = DB::table('college_prospectus')
    //         ->where('subjgroup', $subjgroupId)
    //         ->where('deleted', 0)
    //         ->where(function($q) use($search) {
    //             if($search != '') {
    //                 $q->orWhere('subjCode', 'like', '%' . $search . '%')
    //                   ->orWhere('subjDesc', 'like', '%' . $search . '%');
    //             }
    //         })
    //         ->select(
    //             'subjCode',
    //             'subjDesc',
    //             'lecunits',
    //             'labunits',
    //             'credunits'
    //         )
    //         ->get();
      

    //     // Get the count of records before applying any filters
    //     $subjprospectus_count = DB::table('college_prospectus')
    //         ->where('subjgroup', $subjgroupId)
    //         ->where('deleted', 0)  
    //         ->count();

    //     // Get the count of records after applying the search filter
    //     $filtered_count = $subjprospectus->count();

    //     // Return JSON response
    //     return response()->json([
    //         'data' => $subjprospectus,
    //         'recordsTotal' => $subjprospectus_count,
    //         'recordsFiltered' => $filtered_count
    //     ]);
    // }

    public static function displaySubjperSubjgroup_datatable(Request $request)
    {
        $subjgroupId = $request->get('subjgroup_id');
        $courseid = $request->get('course_id');
        $curriculumid = $request->get('curriculum_id');

        $search = $request->get('search')['value'] ?? '';
        
        // Base query to fetch data from college_prospectus
        $subjprospectus = DB::table('college_prospectus')
            ->where('college_prospectus.subjgroup', $subjgroupId)
            ->where('college_prospectus.deleted', 0)
            ->where(function($q) use($search) {
                if($search != '') {
                    $q->orWhere('college_prospectus.subjCode', 'like', '%' . $search . '%')
                      ->orWhere('college_prospectus.subjDesc', 'like', '%' . $search . '%');
                }
            })
            ->leftJoin('college_subjprereq', function($join) {
                $join->on('college_prospectus.id', '=', 'college_subjprereq.subjID');
                $join->where('college_subjprereq.deleted', 0);
            })
            ->leftJoin('college_prospectus as prereq', 'college_subjprereq.prereqsubjID', '=', 'prereq.id')
            ->select(
                'college_prospectus.id',
                'college_prospectus.subjCode',
                'college_prospectus.subjDesc',
                'college_prospectus.lecunits',
                'college_prospectus.labunits',
                'college_prospectus.credunits',
                // DB::raw('GROUP_CONCAT(DISTINCT prereq.subjDesc SEPARATOR ", ") as prerequisites')
                DB::raw('GROUP_CONCAT(prereq.subjDesc SEPARATOR ", ") as prerequisites')
            )
            ->groupBy('college_prospectus.id', 'college_prospectus.subjCode', 'college_prospectus.subjDesc', 'college_prospectus.lecunits', 'college_prospectus.labunits', 'college_prospectus.credunits')
            ->get();

        // Get the count of records before applying any filters
        $subjprospectus_count = DB::table('college_prospectus')
            ->where('subjgroup', $subjgroupId)
            ->where('deleted', 0)  
            ->count();

        // Get the count of records after applying the search filter
        $filtered_count = $subjprospectus->count();

        // Return JSON response
        return response()->json([
            'data' => $subjprospectus,
            'recordsTotal' => $subjprospectus_count,
            'recordsFiltered' => $filtered_count
        ]);
    }

    



    public static function subjgroup_create(Request $request){

        try{

            $numorder = $request->get('numorder');
            $description = $request->get('description');
            $sort = $request->get('sort');

            $check = DB::table('setup_subjgroups')
                        ->where('description',$description)
                        ->where('deleted',0)
                        ->count();
                        
            if($check > 0){
                return array((object)[
                    'status'=>0,
                    'icon'=>'warning',
                    'message'=>'Already Exist!',
                ]);
            }

            DB::table('setup_subjgroups')
                    ->insert([
                        'sort'=>$sort,
                        'sortnum'=>$numorder,
                        'description'=>$description,
                        'createddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                        'createdby'=>auth()->user()->id,
                        'deleted'=>0
                    ]);

            return array((object)[
                        'status'=>1,
                        'icon'=>'success',
                        'message'=>'Subject Group Created!'
                    ]);
            
        }catch(\Exception $e){
            return self::store_error($e);
        }

    }

    public static function subjgroup_update(Request $request){
        try{

            $numorder = $request->get('numorder');
            $description = $request->get('description');
            $sort = $request->get('sort');
            $id = $request->get('id');

            $check = DB::table('setup_subjgroups')
                        ->where('description',$description)
                        ->where('id','!=',$id)
                        ->where('deleted',0)
                        ->count();
                        
            if($check > 0){
                return array((object)[
                    'status'=>0,
                    'icon'=>'warning',
                    'message'=>'Already Exist!',
                ]);
            }

            DB::table('setup_subjgroups')
                    ->take(1)
                    ->where('id',$id)
                    ->update([
                        'sort'=>$sort,
                        'sortnum'=>$numorder,
                        'description'=>$description,
                        'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                        'updatedby'=>auth()->user()->id,
                        'deleted'=>0
                    ]);

            return array((object)[
                'status'=>1,
                'icon'=>'success',
                'message'=>'Subject Group Updated!'
            ]);

        }catch(\Exception $e){
            return self::store_error($e);
        }
    }

    // public static function subjgroup_delete(Request $request){
    //     try{

    //         $id = $request->get('id');

    //         DB::table('setup_subjgroups')
    //                 ->take(1)
    //                 ->where('id',$id)
    //                 ->update([
    //                     'deleteddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
    //                     'deletedby'=>auth()->user()->id,
    //                     'deleted'=>1
    //                 ]);

    //         return array((object)[
    //             'status'=>1,
    //             'icon'=>'success',
    //             'message'=>'Subject Group Deleted!'
    //         ]);


    //     }catch(\Exception $e){
    //         return self::store_error($e);
    //     }
    // }

    public static function subjgroup_delete(Request $request){
        try{
            $id = $request->get('id');

            // Check if the subject group is a default group
            $defaultGroups = [
                'General Courses',
                'Professional Courses',
                'Major / Specialization Courses',
                'Electives',
                'Mandated Courses (PE & NSTP)'
            ];

            $subjectGroup = DB::table('setup_subjgroups')
                ->where('id', $id)
                ->first();

            if (in_array($subjectGroup->description, $defaultGroups)) {
                // Instead of preventing deletion, set deleted to 1 for default groups
                DB::table('setup_subjgroups')
                    ->where('id', $id)
                    ->update([
                        'deleteddatetime' => \Carbon\Carbon::now('Asia/Manila'),
                        'deletedby' => auth()->user()->id,
                        'deleted' => 1
                    ]);

                return array((object)[
                    'status' => 1,
                    'icon' => 'success',
                    'message' => 'Default Subject Group marked as deleted!'
                ]);
            }

            // For non-default groups, proceed with deletion as before
            DB::table('setup_subjgroups')
                ->where('id', $id)
                ->update([
                    'deleteddatetime' => \Carbon\Carbon::now('Asia/Manila'),
                    'deletedby' => auth()->user()->id,
                    'deleted' => 1
                ]);

            return array((object)[
                'status' => 1,
                'icon' => 'success',
                'message' => 'Subject Group Deleted!'
            ]);

        } catch(\Exception $e){
            return self::store_error($e);
        }
    }


    
    public static function store_error($e){
        DB::table('zerrorlogs')
        ->insert([
                    'error'=>$e,
                    'createdby'=>auth()->user()->id,
                    'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                    ]);
        return array((object)[
              'status'=>0,
              'icon'=>'error',
              'message'=>'Something went wrong!'
        ]);
    }
      
}
