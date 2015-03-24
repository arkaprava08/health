<!DOCTYPE html>
<html>
    <head>
        <title>Admin Page</title>
    </head>
    <body>

        <?php
        $patients = $this->db->query("select * from 
                                    consultation c inner join
                                    patients p on c.patientid = p.id
                                    where c.isChecked = 0 and c.assignedToUser IS NULL");


        $users = $this->db->query("select * from users");
        ?>
        <h1>PATIENTS:</h1>
        <form action="../Health/admin" method="POST">


            <?php
            foreach ($patients->result() as $row) {

                $patientId = $row->id;
                $patientName = $row->name;
                $address = $row->address;
                ?>
                <input type="checkbox" name="patients[]" value="<?php echo $patientId; ?>">
                <?php echo "(".$patientId.")".$patientName . " : " . $address; ?>
                <br>
                <?php
            }
            ?>

            <br><br><br>
            <h2>USERS</h2>

            <?php
            foreach ($users->result() as $row) {

                $id = $row->id;
                $name = $row->username;
                ?>
                <input type="radio" name="user" value="<?php echo $id; ?>"><?php echo $name; ?><br>
                <?php
            }
            ?>

            <br>
            <br>
            <input type="submit" value="SUBMIT" />
        </form>

    </body>
</html>

