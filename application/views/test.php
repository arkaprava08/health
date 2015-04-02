<!DOCTYPE html>
<html>

    <head>
        <style>
            table, th, td {
                border: 1px solid black;
                border-collapse: collapse;
            }
            th, td {
                padding: 15px;
            }
        </style>
    </head>

    <body>
        <h1>PATIENTS</h1>
        <table style="width:100%">  
            <tr>
                <th>ID</th>
                <th>NAME</th>		
                <th>DOB</th>
                <th>GENDER</th>
                <th>HEIGHT</th>		
                <th>WEIGHT</th>
                <th>ADDRESS</th>
                <th>USERID</th>	
            </tr>

            <?php
            $q = $this->db->query("SELECT * FROM patients");

            foreach ($q->result() as $row) {

                echo "<tr>";

                foreach ($row as $d) {
                    echo "<td>";

                    echo $d;

                    echo "</td>";
                }
                echo "</tr>";
            }
            ?>

        </table>
        
        <br>
        <h1>PATIENTS DATA</h1>
        <table style="width:100%">  
            <tr>
                <th>ID</th>
                <th>PATIENTID</th>		
                <th>USERID</th>
                <th>BODYTEMPERATURE</th>
                <th>TEMP-TYPE</th>
                <th>BP_SP</th>		
                <th>BP_DP</th>
                <th>SYMTOMS</th>
                <th>COMMENT</th>
                <th>LATITUDE</th>
                <th>LONGITUDE</th>
                <th>INSERTED DATE</th>
                <th>CONSUL TATION</th>
            </tr>

            <?php
            $q1 = $this->db->query("SELECT * FROM patientdata");

            foreach ($q1->result() as $row) {

                echo "<tr>";

                foreach ($row as $d) {
                    echo "<td>";

                    echo $d;

                    echo "</td>";
                }
                echo "</tr>";
            }
            ?>

        </table>
        
        <br>
        <h1>ACTION DETAIL</h1>
        <table style="width:100%">  
            <tr>
                <th>ID</th>
                <th>PATIENTDATAID</th>		
                <th>ACTIONDETAILS</th>
                <th>DATE</th>
            </tr>

            <?php
            $q2 = $this->db->query("SELECT * FROM action");

            foreach ($q2->result() as $row) {

                echo "<tr>";

                foreach ($row as $d) {
                    echo "<td>";

                    echo $d;

                    echo "</td>";
                }
                echo "</tr>";
            }
            ?>

        </table>
        
        
    </body>
</html>



