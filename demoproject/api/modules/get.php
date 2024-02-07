<?php

/**
 * Get Class
 *
 * This PHP class provides methods for retrieving data related to employees and jobs.
 *
 * Usage:
 * 1. Include this class in your project.
 * 2. Create an instance of the class to access the provided methods.
 * 3. Call the appropriate method to retrieve the desired data.
 *
 * Example Usage:
 * ```
 * $get = new Get();
 * $employeesData = $get->get_employees();
 * $jobsData = $get->get_jobs();
 * ```
 *
 * Note: Customize the methods as needed to fetch data from your actual data source (e.g., database, API).
 */

class Get
{
    private $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function get_records($table, $condition = null){
        $sqlString = "SELECT * FROM $table";
        if ($condition != null) {
            $sqlString .= " WHERE " . $condition;
        }
        $result = $this->executeQuery($sqlString);
        if($result['code']==200){
            return $this->sendPayload($result['data'], 'Success', 'Successfully retrieved records', $result['code']);
        }
        return $this->sendPayload(null, 'failed', 'Failed to retrieve records', $result['code']);
    }


    public function sendPayload($data, $remarks, $message, $code){
        $status = array("remarks" => $remarks, "message" => $message);
        http_response_code($code);
        return array(
            "status"=> $status,
            "payload"=> $data,
            "prepared_by"=> "Renz Joshua Anciro",
            "time_stamp"=> date_create()
        );
    }



    public function executeQuery($sqlString)
    {
        $data = array();
        $errmsg = "";
        $code = 0;

        // $result = $this->pdo->query($sql)->fetchAll(
        try {
            if ($result = $this->pdo->query($sqlString)->fetchAll()) {
                foreach ($result as $record) {
                    array_push($data, $record);
                }

                $code = 200;
                $result = null;
                return array("code" => $code, "data" => $data);
            } else {
                $errmsg = "No data found";
                $code = 404;
            }
        } catch (\PDOException $e) {
            $errmsg = $e->getMessage();
            $code = 403;
        }
        return array("code" => $code, "errmsg" => $errmsg);
    }


    /**
     * Retrieve a list of employees.
     *
     * @return string
     *   A string representing the list of employees.
     */
    public function get_employees($id = null)
    {
        $conditionString = null;
        if($id != null) {
        $conditionString = "EMPLOYEE_ID=$id";
        }

        return $this->get_records("employees", $conditionString);
    }

    /**
     * Retrieve a list of jobs.
     *
     * @return string
     *   A string representing the list of jobs.
     */
    public function get_jobs()
    {
        return "This is my list of jobs";
    }
}
