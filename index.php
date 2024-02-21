<?php
require 'db_conn.php';
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE-edge">
        <meta name="viewport" content="width=device-width,initial-scale=1.0">
        <title>TO DO LIST</title>
        <link rel="stylesheet" href="style.css" type="text/css">
    </head>

    <body>
        <div class="container">
            <div class="box">
                <h2>To Do List</h2>
                <form action="add_data.php" method="POST" autocomplete="off">
                    <?php if(isset($_GET['mess']) && $_GET['mess'] == 'error') { ?>
                        <input type="text" 
                            name="title" 
                            style="border-style:solid;border-width:medium;border-color: #ff6666 !important"
                            placeholder="This field is required" 
                            id="input8x">
                        <button type="submit">Add &nbsp; <span>&#43;</span></button>
                    <?php } else { ?>
                        <input type="text" name="title" placeholder="What do you need to do?" id="input8x">
                        <button type="submit">Add &nbsp; <span>&#43;</span></button>
                    <?php } ?>
                </form>

                <?php
                    $todos = $conn->query("SELECT * FROM todos ORDER BY checked ASC");
                ?>

                <ul  id="list">
                <?php while($todo = $todos->fetch(PDO::FETCH_ASSOC)) { ?>
                    <?php if($todo['checked']){ ?>
                        <li class="checked done" data-todo-id = "<?php echo $todo['id']; ?>">
                            <?php echo $todo['title'] ?>
                            <i id="<?php echo $todo['id']; ?>" class="remove-to-do"></i>    
                        </li>                           
                    <?php }else{ ?>
                        <li data-todo-id = "<?php echo $todo['id']; ?>">
                            <?php echo $todo['title'] ?>
                            <i id="<?php echo $todo['id']; ?>"  class="remove-to-do"></i>
                        </li>          
                    <?php } ?>
                <?php } ?>
                </ul>
            </div>
        </div>

        <script src="js/jquery-3.2.1.min.js"></script>
        <script>
            $(document).ready(function(){
                $('.remove-to-do').click(function(){
                    const id = $(this).attr('id');

                    console.log(id)
                    
                    $.post("remove_data.php",
                        {
                            id : id
                        },
                        (data) => {
                            if(data){
                                    $(this).parent().hide(600);
                            }
                        }
                    );
            });

                $('.box li').click(function(e){
                        const id = $(this).attr('data-todo-id');
                        //console.log(id);
                        
                        $.post('check_data.php',
                            {
                                id : id
                            },
                            (data) => {
                                if(data != 'error'){
                                        const li = $(this);
                                        if(data === '1'){
                                                li.removeClass('done');
                                                li.removeClass('checked');
                                        }else{
                                                li.addClass('done');
                                                li.addClass('checked');
                                        }
                                }
                            }
                        )
                    });
            });
            // let input8x = document.querySelector('#input8x');
            // let list = document.querySelector('#list');

            // input8x.addEventListener("keyup", function(event){
            //     if(event.key == "Enter"){
            //         addItem(this.value)
            //         this.value = ""
            //     }
            // })

            // let addItem = (input8x) => {
            //     let listItem = document.createElement("li");
            //     listItem.innerHTML = `${input8x}<i>`;

            //         listItem.addEventListener("click",function(){
            //             this.classList.toggle('done');
            //         })

            //         listItem.querySelector("i").addEventListener("click",
            //         function(){
            //             listItem.remove();
            //         })
            //         list.appendChild(listItem);
            // }
        </script>
    </body>
</html>