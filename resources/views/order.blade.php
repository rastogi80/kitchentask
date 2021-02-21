<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <title>My Kitchen</title>
</head>
<body>
<form method="POST" action="{{url('order-process')}}" enctype="multipart/form-data">
    @csrf
<div class="container">
    <div class="form-group">
            @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
    <div class="form-group">
    <label for="csv">CSV File</label>
    <input type="file" class="form-control" id="csvfile" name="csvfile" aria-describedby="csv" placeholder="Enter Kitchen Product's CSV">
  </div>
  <div class="form-group">
    <label for="order">Your Order</label>
    <input type="file" class="form-control" id="foodorder"  name="foodorder" placeholder="Order in Json File">
  </div>
 
  <button type="submit" class="btn btn-primary">Submit</button>
</div>
</form>
</body>
</html>