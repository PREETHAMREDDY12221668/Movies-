<div class="block">
    <div class="wrap">
        <form action="process_search.php" id="reservation-form" method="post" onsubmit="return validateSearch();" style="text-align: center;">
            <fieldset style="border: none; padding: 0; margin: 0;">
                <div class="field" style="display: flex; justify-content: center; align-items: center;">
                    <input
                        type="text"
                        placeholder="Search Movies Here..."
                        required
                        id="search111"
                        name="search"
                       
                    />
                    <!-- <input
                        type="submit"
                        value="Search"
                        id="button111"
                        style="
                            margin-left: 10px;
                            padding: 10px 20px;
                            font-size: 16px;
                            border: none;
                            border-radius: 30px;
                            background-color: #ff9800;
                            color: #121212;
                            cursor: pointer;
                            outline: none;
                            transition: background-color 0.3s ease;
                        "
                    /> -->
                </div>
            </fieldset>
        </form>
        <div class="clear" style="clear: both;"></div>
    </div>
</div>

<script>
    function validateSearch() {
        const searchInput = document.getElementById('search111');
        if (searchInput.value.trim() === "") {
            alert("Please enter a movie name...");
            return false;
        }
        return true;
    }
</script>
