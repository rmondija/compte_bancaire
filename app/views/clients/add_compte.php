<form method="POST">
    <input type="text" name="numero_compte" placeholder="Numéro de compte" required>
    <input type="number" name="solde" placeholder="Solde" required>
    <select name="type_compte">
        <option value="courant">Courant</option>
        <option value="épargne">Épargne</option>
    </select>
    <button type="submit">Ajouter</button>
</form>