window.onload =  () => {
  let movies = document.getElementsByClassName('movie-card');
  for(let movie of movies){
    movie.addEventListener('click', () => {
      window.location = "movie.php?id=" + movie.id;
    });
  }

  let typeFilter = document.getElementById('filter-select');
  typeFilter.addEventListener('change', () => {
    if(typeFilter.value === "0"){
      window.location = "index.php";
    }else{
      window.location = "index.php?gatunek=" + typeFilter.value;
    }
  });

  let availableFilter = document.getElementById('filter-available-select');
  availableFilter.addEventListener('change', () => {
    if(availableFilter.value === "0"){
      window.location = "index.php";
    }else{
      if(typeFilter.value === "0"){
        window.location = "index.php?dostepny=" + availableFilter.value;
      }else{
        window.location = window.location + "&dostepny=" + availableFilter.value;
      }

    }
  });
};