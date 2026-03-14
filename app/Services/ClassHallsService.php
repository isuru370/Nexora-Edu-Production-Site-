<?php

namespace App\Services;

use App\Models\ClassHalls;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ClassHallsService
{
    public function fetchClassHalls()
    {
        try {
            // Fetch all class halls (without status filter)
            $result = ClassHalls::all();

            if ($result->isNotEmpty()) {
                return response()->json([
                    'status' => true,
                    'data' => $result
                ], 200);
            }

            return response()->json([
                'status' => false,
                'message' => 'No class halls found'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to fetch class halls data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function storeClassHall(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'hall_id' => 'required|string|max:255|unique:class_halls,hall_id',
            'hall_name' => 'required|string|max:255',
            'hall_type' => 'required|string|max:45', // Changed to 45 to match table
            'hall_price' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Create new class hall WITHOUT status
            $classHall = ClassHalls::create([
                'hall_id' => $request->hall_id,
                'hall_name' => $request->hall_name,
                'hall_type' => $request->hall_type,
                'hall_price' => $request->hall_price,
                // status column removed
            ]);

            return response()->json([
                'status' => true,
                'data' => $classHall,
                'message' => 'Class hall created successfully'
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to create class hall',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function fetchDropdownHalls()
    {
        try {
            // Fetch all halls (no status filter)
            $halls = ClassHalls::select('id', 'hall_id', 'hall_name', 'hall_type', 'hall_price')
                ->get(); // Removed where('status', 1)

            return response()->json([
                'status' => 'success',
                'data' => $halls
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch halls for dropdown',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function fetchClassHall($id)
    {
        try {
            $classHall = ClassHalls::find($id);

            if (!$classHall) {
                return response()->json([
                    'status' => false,
                    'message' => 'Class hall not found'
                ], 404);
            }

            return response()->json([
                'status' => true,
                'data' => $classHall
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to fetch class hall',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function updateClassHall(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'hall_id' => 'required|string|max:255|unique:class_halls,hall_id,' . $id . ',id',
            'hall_name' => 'required|string|max:255',
            'hall_type' => 'required|string|max:45', // Changed to 45
            'hall_price' => 'required|numeric|min:0',
            // ❌ Remove status validation
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $classHall = ClassHalls::find($id);

            if (!$classHall) {
                return response()->json([
                    'status' => false,
                    'message' => 'Class hall not found'
                ], 404);
            }

            $classHall->update([
                'hall_id' => $request->hall_id,
                'hall_name' => $request->hall_name,
                'hall_type' => $request->hall_type,
                'hall_price' => $request->hall_price,
                // ❌ Remove status update
            ]);

            return response()->json([
                'status' => true,
                'data' => $classHall,
                'message' => 'Class hall updated successfully'
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to update class hall',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
