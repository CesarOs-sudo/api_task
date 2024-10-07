<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{

    /**
     * *Funcion encargada de validar la informacion proporcionada por POST
     * Contiene reglas de validacion, mensajes
     * @param $request : arreglo de datos de tarea
     * @param {boolean} $isUpdate: bandera para identificar si la funcion es llamada desde una accion update
     * @return respuesta de validator
     */
    private function validateForm( $request, $isUpdate = false){
        $messages = [
            'required' => 'El campo :attribute es obligatorio',
            'name_task.min' => 'El campo :attribute tiene que contener mas de :min caracteres',
            'name_task.string' => 'El campo :attribute tiene que ser una cadena de texto',
            'description_task.min' => 'El campo :attribute tiene que contener mas de :min caracteres',
            'description_task.string' => 'El campo :attribute tiene que ser una cadena de texto',
            'status.string' => 'El campo :attribute tiene que ser una cadena de texto',
            'status.in' => 'El campo :attribute solo acepta el dato Pendiente o Realizado'
        ];

        $rules =  [
            'name_task' => 'string|min:5|max:25',
            'description_task' => 'string|min:10|max:250',
            'status' => 'in:Pendiente,Realizado'
        ];

        if( !$isUpdate ){
            $rules['name_task'] = 'required|string|min:5|max:25';
            $rules['description_task'] = 'required|string|min:5|max:255';
            $rules['status'] = 'required|string|min:9|max:9';
            $rules['status'] = 'required|in:Pendiente,Realizado';
        } 
        return Validator::make($request, $rules ,$messages);
    }

    /**
     * *Funcion que retorna la lista de tareas de un usuario autenticado
     * @return json 
     */
    public function getTaskForUser(){
        try {
            $resp = [];
            $code = 0;
            $user = auth()->user(); 
            if( $user ){
                $tasks = Task::where('user_id', $user->id)->where('available', 1)->get();
                $code = 200;
                $resp = [
                    "status" => true,
                    "message" => "Lista de tareas",
                    "data" => $tasks
                ];
            }else{
                $code = 400;
                $resp = [
                    "status" => false,
                    "message" => "Lo sentimos, usuario no autenticado. Favor de autenticarse"
                ];
            }
            return response()->json($resp, $code);
        } catch (\Throwable $th) {
            $resp = [
                "status" => false,
                "message" => "Lo sentimos, Ha ocurrido un error. Favor de intentarlo mas tarde"
            ]; 
            return response()->json($resp, 500);
        }
    }

    /**
     * *Funcion que crea una tarea nueva
     * @param Request $request Solicitud que contiene los datos para crear la tarea
     * @return JsonResponse: Respuesta JSON con los datos de la tarea creada o los mensajes de error
     */
    public function createTask(Request $request){
        try {
            $resp = [];
            $code = 400;
            $validator = $this->validateForm($request->all());
            if(!$validator->fails()){
                $task = new Task();
                $task->user_id = auth()->user()->id;
                $task->name_task = $request->name_task;
                $task->description_task = $request->description_task;
                $task->status = $request->status;
                $task->available = 1;
                $save = $task->save();
                if($save){
                    $code = 201;
                    $resp = [
                        "status" => "true", 
                        "message" => "Tarea almacenada satisfactoriamente",
                        "task" => $task
                    ];   
                }else{
                    $code = 400;
                    $resp = [
                        "status" => "false",
                        "message" => "Tarea no creada. Favor de intentarlo mas tarde", 
                    ]; 
                }
            }else{
                $code = 400;
                $resp = [
                    "status" => "false",
                    "message" => "Información no valida. Favor de revisar la información proporcionada",
                    "errors" => $validator->errors()->getMessages()
                ]; 
            }
            return response()->json($resp, $code);
        } catch (\Throwable $th) {
            $resp = [
                "status" => false,
                "message" => "Lo sentimos, Ha ocurrido un error. Favor de intentarlo mas tarde",
                "errors" => $th->getMessage()
            ]; 
            return response()->json($resp, 500);
        }
    }

    /**
     * *Funcion que actualizar una tarea
     * @param Request $request Solicitud que contiene los datos a actualizar de la tarea
     * @param $id : Id de la tarea a la cual se le actualizaran los datos
     * @return JsonResponse: Respuesta JSON con los datos de la tarea actualizada
     */
    public function updateTask(Request $request, $id){
        try {
            $resp = [];
            $code = 400;
            $validator = $this->validateForm($request->all());
            $userId = auth()->user()->id;
            if($validator->fails()){
                // $task = Task::findOrFail($id);
                $task = Task::where('user_id', $userId)->where('id', $id)->where('available', 1)->first();
                if($task){
                    $task->update($request->all());
                    $code = 200;
                    $resp = [
                        "status" => true,
                        "title" => "Tarea actualizada satisfactoriamente",
                        "message" => "",
                        "task" => $task
                    ]; 
                }else{
                    $code = 404;
                    $resp = [
                        "status" => false,
                        "message" => "Tarea no encontrada. El id proporcionado no pertenece a una tarea almacenada",
                    ]; 
                } 
            }else{
                $code = 400;
                $resp = [
                    "status" => false,
                    "message" => "Información no valida. Favor de revisar la información proporcionada",
                    "errors" => $validator->errors()->getMessages()
                ]; 
            }
            return response()->json($resp, $code);
        } catch (\Throwable $th) {
            $resp = [
                "status" => false,
                "message" => "Lo sentimos, Ha ocurrido un error. Favor de intentarlo mas tarde",
                "errors" => $th->getMessage()
            ]; 
            return response()->json($resp, 500);
        }
    }

    /**
     * *Funcion que elimina una tarea relacionada con el usuario
     * @param $id : Id de la tarea a la cual se desea eliminar
     * @return JsonResponse: Respuesta JSON con que notifica si fue o no eliminada la tarea
     */
    public function deleteTask($id){
        try {
            $resp = [];
            $code = 400;
            $userId = auth()->user()->id; 
            $taskUser = Task::where('id', $id)->where('user_id', $userId)->where('available', 1)->first();
            if($taskUser){
                /**
                 * TODO: No es recomendado eliminar el registro de la base de datos
                 * Solo basta con hacer update para que el registro no sea visible
                 * $taskDelete = Task::where("id", $id)->update(["available" => 0]); 
                 */
                $taskDelete = Task::where("id", $id)->delete();
                if($taskDelete){
                    $code = 200;
                    $resp = [
                        "status" => true,
                        "message" => "Tarea eliminada satisfactoriamente", 
                    ];
                }else{
                    $code = 400;
                    $resp = [
                        "status" => false,
                        "title" => "Lo sentimos la tarea no pudo ser eliminada. Intentelo mas tarde",
                        "message" => "",
                    ];
                }
            }else{
                $code = 404;
                $resp = [
                    "status" => false, 
                    "message" => "La tarea que desea eliminar no fue encontrada",
                ];
            }
            return response()->json($resp, $code);
        } catch (\Throwable $th) {
            $resp = [
                "status" => false,
                "message" => "Lo sentimos, Ha ocurrido un error. Favor de intentarlo mas tarde",
                "errors" => $th->getMessage()
            ]; 
            return response()->json($resp, 500);
        }
    }

    

}

