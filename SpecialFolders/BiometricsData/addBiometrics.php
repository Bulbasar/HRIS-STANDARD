<?php
    $empid = isset($_GET['empid']) ? $_GET['empid'] : '';
?>

<div>
    <?php
        echo
        "
        <div class='w-75 h-100 d-flex flex-row justify-content-between align-items-center' style='margin:auto' >
            <div class='d-flex flex-column w-50 justify-content-center align-items-center'>
                <button class='bioBtn' style='background-color: inherit; border: none'  onclick='addFace($empid)'}'><img src='img/face.png' style='width: 12em; height: 13em'></button>
                <h4 class=''>Add Face Recognition </h4>
            </div>
            
            <div class='d-flex flex-column w-50 justify-content-center align-items-center'>
                <button class='bioBtn' onclick='addFingerprint($empid)' style='background-color: inherit; border: none'><img src='img/fingerprint.png' style='width: 12em; height: 13em'></button>
                <h4 class='' style=''>Add Fingerprint </h4>
            </div>
            
        </div>
        ";
    ?>
</div>

<!-- <script type="text/javascript">
    function addFace(empid){
        console.log(`face for: ${empid}`);
    }
    function addFingerprint(empid){
        console.log(`fingerprint for: ${empid}`);
    }
</script> -->
