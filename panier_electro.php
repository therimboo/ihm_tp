<?php 
    $conn = mysqli_connect('localhost','root','132132','tp_ihm');
?>
<html>
    <h1>Products</h1>
</html>
<?php
    $product_query = "select * from produit";
    $product_query_rs = mysqli_query($conn,$product_query);
    while($row = mysqli_fetch_array($product_query_rs)){
        $id = $row['id'];
        print "".$row['nomp']. ":\t ".$row['prix']." DA";?>
        <html> 
            <span>
                <style>
                    input[type=number]{
                        width: 40px;
                    } 
                </style>
                <form method="post">
                    <input type="hidden" value= "<?php echo "$id" ?>" name="ajout_">
                    <input type="number" name="nombre" min="1">
                    <input type="submit" value="ajouter" name="ajouter">
                </form>
            </span>
            <br>
        </html>  
        <?php
    }
?>
 
<?php
    if(isset($_POST['ajouter'])){
        $nombre = intval($_POST['nombre']);
        if($nombre > 0){
            $id = $_POST['ajout_'];
            $find_id = "select * from produit where id = $id ";
            $find_id_query = mysqli_query($conn,$find_id);
            $find_id_query = mysqli_fetch_array($find_id_query);
            $prix = intval($find_id_query['prix']);
            $nomp = $find_id_query['nomp'];
            $existance = "select * from panier where id = $id";
            $existance_query = mysqli_query($conn,$existance);
            $existance_query = mysqli_fetch_array($existance_query);
            if($existance_query){
                $nombrep = intval($existance_query['nombre']);
                $nombrep = $nombrep + $nombre;
                $prix = $prix * $nombrep;
                $stmt = $conn->prepare("update panier set nombre = $nombrep, prix =$prix where id = $id");
                $stmt->execute();
                $stmt->close();
            }
            else{
                $prix = $prix * $nombre;
                $stmt = $conn->prepare("insert into panier(id,nomp,prix,nombre) values(?,?,?,?)");
                $stmt->bind_param("ssss", $id,$nomp,$prix,$nombre);
                $stmt->execute();
                $stmt->close();
            }
        }  
    }
    if(isset($_POST['cancel'])){
        $id = $_POST['cancel_id'];
        $stmt = $conn->prepare("delete from panier where id=$id");
        $stmt->execute();
        $stmt->close();
    }
?>
<html>
    <style>
        div{
            position: absolute;
            bottom: 40%;
            left: 30%;
        }
    </style>
    <hr>
    <h1>Pannier</h1>
    <?php
        $prix_total = 0;
        $panier_query = "select * from panier";
        $panier_query_rs = mysqli_query($conn,$panier_query);
        while ($row_panier = mysqli_fetch_array($panier_query_rs)){
            $id_panier = $row_panier['id'];
            $prix_total = $prix_total + intval($row_panier['prix']); 
            print "(".$row_panier['nombre'].")  ".$row_panier['nomp'].":\t".$row_panier['prix']." DA";
            ?>
            <html>
                <form method="post">
                    <input type="hidden" value = "<?php echo "$id_panier" ?>" name="cancel_id">
                    <input type="submit" value="cancel" name="cancel">
                </form>
            </html>
        <?php
        }
    ?>
    <div><p> <h4>prix total:</h4> <?php print $prix_total ?></p></div>
   
    
</html>
