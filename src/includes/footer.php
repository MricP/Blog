    <footer>
        <div></div>
    </footer>
    <script>
        function updateCharacterCount($totalCarac) {
            const textarea = document.getElementById('textarea');
            const characterCount = document.getElementById('characterCount');
            const count = textarea.value.length; // Obtient le nombre de caractères
            characterCount.textContent = `${count} / ${$totalCarac}`; // Met à jour le texte
        }
    </script>
</body>
</html>