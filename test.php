<?php
Class Task
{
    public function getNumberRatio($arr)
    {
        $positive=0; $negative =0; $zero=0;
        foreach($arr as $a)
        {
            if($a==0)
            {
                $zero +=1;
            }
            elseif ($a>0) {
                $positive +=1;
            }
            else{
                $negative +=1;
            }
        }
        echo number_format($positive/5,5);
        echo"<br>";
        echo number_format($negative/5,5);
        echo"<br>";
        return number_format($zero/5,5);
    }
    public function getMaxMin($arr)
    {
        sort($arr);
        echo '<br>';
        $min=0;
        $max=0;
        print_r($arr);
        foreach ($arr as $key =>$a)
        {
            $max +=0;
            $min +=0;
            if($key !=0)
            {
                $max +=$a;
            }
            if($key != count($arr) - 1)
            {
                $min +=$a;
            }
        }
        echo $min.'  '.$max;

    }


    function quickSort($arr)
    {
        // Base case: If the array has 1 or 0 elements, it is already sorted
        if (count($arr) <= 1) {
            return $arr;
        }

        // Choose a pivot element (in this case, the first element)
        $pivot = $arr[0];

        // Initialize two empty arrays for elements less than and greater than the pivot
        $left = $right = [];

        // Iterate through the array starting from the second element
        for ($i = 1; $i < count($arr); $i++) {
            // If the current element is less than the pivot, add it to the left array
            if ($arr[$i] < $pivot) {
                $left[] = $arr[$i];
            }
            // If the current element is greater than or equal to the pivot, add it to the right array
            else {
                $right[] = $arr[$i];
            }
        }

        // Recursively apply quickSort to the left and right arrays, and concatenate the results
        return array_merge($this->quickSort($left), [$pivot], $this->quickSort($right));
    }


}


$task = new Task();
print_r($task->quickSort([5, 2, 4, 1, 3]));

?>
